<template>
  <div class="vehiculos-container">
    <div class="page-header">
      <div class="header-content">
        <h2><i class="pi pi-car"></i>Gestión de Vehículos</h2>
        <button class="btn-add" @click="openDialog()">
          <i class="pi pi-plus"></i>
          Nuevo Vehículo
        </button>
      </div>
      <div class="filter-container">
        <div class="search-box">
          <i class="pi pi-search"></i>
          <input v-model="filtro" type="text" placeholder="Buscar por matrícula..." class="form-control" />
          <button v-if="filtro" @click="filtro = ''" class="clear-search">
            <i class="pi pi-times"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Indicador de carga -->
    <div v-if="loading" class="loading-container">
      <div class="loader-animation">
        <i class="pi pi-spin pi-spinner"></i>
      </div>
      <span>Cargando vehículos...</span>
    </div>

    <!-- Tabla de vehículos -->
    <div v-else class="table-container">
      <vehiculos-table :vehiculos="vehiculosFiltrados" @edit="editVehiculo" @delete="deleteVehiculo" />

      <div v-if="vehiculosFiltrados.length === 0" class="no-results">
        <i class="pi pi-exclamation-circle"></i>
        <p>No se encontraron vehículos{{ filtro ? ' con la matrícula "' + filtro + '"' : '' }}</p>
      </div>
    </div>

    <!-- Componente de formulario -->
    <vehiculo-form v-model:visible="dialogVisible" :vehiculo="vehiculoForm" :dialog-header="dialogHeader"
      @save="saveVehiculo" @close="closeDialog" />
  </div>
</template>

<script>
import VehiculosTable from './VehiculosTable.vue';
import VehiculoForm from './VehiculosForm.vue';

export default {
  name: 'VehiculosView',
  components: {
    VehiculosTable,
    VehiculoForm
  },
  data() {
    return {
      vehiculos: [],
      filtro: '',
      loading: false,
      dialogVisible: false,
      dialogHeader: 'Nuevo Vehículo',
      vehiculoForm: this.getEmptyVehiculoForm()
    };
  },
  computed: {
    vehiculosFiltrados() {
      if (!Array.isArray(this.vehiculos)) return [];
      return this.vehiculos.filter(vehiculo =>
        vehiculo.matricula.toLowerCase().includes(this.filtro.toLowerCase())
      );
    }
  },
  mounted() {
    this.cargarVehiculos();
  },
  methods: {
    getEmptyVehiculoForm() {
      return {
        matricula: '',
        marca: null,
        modelo: null,
        color: '#000000',
        tipovehiculo: null,
        estado: 'En depósito'
      };
    },
    async cargarVehiculos() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$parent.isAuthenticated = false;
        this.$router.push('/login');
        return;
      }

      this.loading = true;
      try {
        const response = await fetch('/api/vehiculos', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });

        if (!response.ok) {
          if (response.status === 401) {
            localStorage.removeItem('token');
            this.$parent.isAuthenticated = false;
            this.$router.push('/login');
            return;
          }
          throw new Error('Error al cargar vehículos');
        }

        this.vehiculos = await response.json();
      } catch (error) {
        console.error('Error al cargar vehículos:', error);
        if (error.message.includes('Failed to fetch')) {
          localStorage.removeItem('token');
          this.$parent.isAuthenticated = false;
          this.$router.push('/login');
          return;
        }
        this.vehiculos = [];
      } finally {
        this.loading = false;
      }
    },
    openDialog(vehiculo = null) {
      this.dialogHeader = vehiculo ? 'Editar Vehículo' : 'Nuevo Vehículo';
      this.vehiculoForm = vehiculo ? { ...vehiculo } : this.getEmptyVehiculoForm();
      this.dialogVisible = true;
    },
    closeDialog() {
      this.dialogVisible = false;
    },
    editVehiculo(vehiculo) {
      this.openDialog(vehiculo);
    },
    deleteVehiculo(vehiculo) {
      if (confirm(`¿Estás seguro de eliminar el vehículo ${vehiculo.matricula}?`)) {
        this.eliminarVehiculo(vehiculo.id);
      }
    },
    async eliminarVehiculo(id) {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$parent.isAuthenticated = false;
        this.$router.push('/login');
        return;
      }

      try {
        const response = await fetch(`/api/vehiculos/${id}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });
        if (!response.ok) {
          if (response.status === 401) {
            localStorage.removeItem('token');
            this.$parent.isAuthenticated = false;
            this.$router.push('/login');
            return;
          }
          throw new Error('Error al eliminar vehículo');
        }
        await this.cargarVehiculos();
      } catch (error) {
        console.error('Error al eliminar:', error);
        if (error.message.includes('Failed to fetch')) {
          localStorage.removeItem('token');
          this.$parent.isAuthenticated = false;
          this.$router.push('/login');
          return;
        }
      }
    },
    async saveVehiculo(vehiculo) {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$parent.isAuthenticated = false;
        this.$router.push('/login');
        return;
      }

      try {
        const method = vehiculo.id ? 'PUT' : 'POST';
        const url = vehiculo.id ? `/api/vehiculos/${vehiculo.id}` : '/api/vehiculos';

        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          },
          body: JSON.stringify(vehiculo)
        });

        if (!response.ok) {
          if (response.status === 401) {
            localStorage.removeItem('token');
            this.$parent.isAuthenticated = false;
            this.$router.push('/login');
            return;
          }
          const errorData = await response.json();
          throw new Error(errorData.errors ? Object.values(errorData.errors).flat().join(', ') : 'Error al guardar vehículo');
        }

        await this.cargarVehiculos();
        this.closeDialog();
      } catch (error) {
        console.error('Error al guardar:', error);
        if (error.message.includes('Failed to fetch')) {
          localStorage.removeItem('token');
          this.$parent.isAuthenticated = false;
          this.$router.push('/login');
          return;
        }
      }
    }
  }
};
</script>

<style scoped>
.vehiculos-container {
  background-color: #f8f9fa;
  border-radius: 10px;
  padding: 1rem; /* Reduced padding */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 1rem; /* Reduced margin */
  display: flex;
  flex-direction: column;
  gap: 0.8rem; /* Reduced gap */
  border-bottom: 1px solid #e9ecef;
  padding-bottom: 0.8rem; /* Reduced padding */
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-content h2 {
  color: var(--primary-color);
  margin: 0;
  font-size: 1.5rem; /* Smaller font */
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.header-content h2 i {
  font-size: 1.3rem; /* Smaller icon */
}

.btn-add {
  background-color: var(--secondary-color);
  color: white;
  border: none;
  padding: 0.5rem 1rem; /* Reduced padding */
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
  font-size: 0.9rem; /* Smaller font */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.btn-add:hover {
  background-color: var(--primary-color);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-add:active {
  transform: translateY(0);
}

.filter-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.search-box {
  position: relative;
  width: 100%;
  max-width: 400px;
}

.search-box i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-color);
  opacity: 0.6;
  font-size: 0.9rem; /* Smaller icon */
}

.search-box input {
  width: 100%;
  border-radius: 6px;
  border: 1px solid #ddd;
  padding: 0.6rem 2.2rem 0.6rem 2.2rem; /* Reduced padding */
  font-size: 0.9rem; /* Smaller font */
  transition: all 0.3s ease;
  background-color: white;
}

.search-box input:focus {
  border-color: var(--secondary-color);
  box-shadow: 0 0 0 3px rgba(var(--secondary-color-rgb), 0.2);
  outline: none;
}

.clear-search {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6c757d;
  cursor: pointer;
  padding: 3px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem; /* Smaller icon */
}

.clear-search:hover {
  background-color: #f1f3f5;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px; /* Reduced gap */
  padding: 2rem; /* Reduced padding */
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.loader-animation {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50px; /* Smaller loader */
  width: 50px; /* Smaller loader */
  border-radius: 50%;
  background-color: rgba(var(--secondary-color-rgb), 0.1);
}

.loader-animation i {
  font-size: 1.8rem; /* Smaller icon */
  color: var(--secondary-color);
}

.loading-container span {
  font-size: 1rem; /* Smaller font */
  color: #6c757d;
}

.table-container {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.no-results {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem; /* Reduced padding */
  color: #6c757d;
  text-align: center;
}

.no-results i {
  font-size: 2.5rem; /* Smaller icon */
  margin-bottom: 0.8rem; /* Reduced margin */
  color: #dee2e6;
}

.no-results p {
  font-size: 1rem; /* Smaller font */
  margin: 0;
}

@media (max-width: 768px) {
  .vehiculos-container {
    padding: 0.8rem; /* Further reduced for mobile */
    border-radius: 6px;
  }

  .header-content {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.8rem; /* Reduced gap */
  }

  .btn-add {
    width: 100%;
    justify-content: center;
  }

  .search-box {
    max-width: 100%;
  }
}
</style>