<template>
  <div class="container">
    <div class="page-header">
      <div class="header-content">
        <h2>Gestión de Vehículos</h2>
        <button class="btn-add" @click="openDialog()">
          <i class="pi pi-plus"></i>
          Nuevo Vehículo
        </button>
      </div>
      <div class="filter-container">
        <div class="search-box">
          <i class="pi pi-search"></i>
          <input v-model="filtro" type="text" placeholder="Buscar por matrícula..." class="form-control" />
        </div>
      </div>
    </div>

    <!-- Indicador de carga -->
    <div v-if="loading" class="loading-container">
      <i class="pi pi-spin pi-spinner"></i>
      <span>Cargando vehículos...</span>
    </div>

    <!-- Tabla de vehículos -->
    <vehiculos-table 
      v-else
      :vehiculos="vehiculosFiltrados"
      @edit="editVehiculo"
      @delete="deleteVehiculo"
    />

    <!-- Componente de formulario -->
    <vehiculo-form
      v-model:visible="dialogVisible"
      :vehiculo="vehiculoForm"
      :dialog-header="dialogHeader"
      @save="saveVehiculo"
      @close="closeDialog"
    />
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
        estado: 'Activo'
      };
    },
    async cargarVehiculos() {
      this.loading = true;
      try {
        const response = await fetch('/api/vehiculos', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.vehiculos = await response.json();
      } catch (error) {
        console.error('Error al cargar vehículos:', error);
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
      try {
        const response = await fetch(`/api/vehiculos/${id}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        if (response.ok) {
          this.cargarVehiculos();
        }
      } catch (error) {
        console.error('Error al eliminar:', error);
      }
    },
    async saveVehiculo(vehiculo) {
      try {
        const method = vehiculo.id ? 'PUT' : 'POST';
        const url = vehiculo.id ? `/api/vehiculos/${vehiculo.id}` : '/api/vehiculos';
        
        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify(vehiculo)
        });
        
        if (response.ok) {
          this.cargarVehiculos();
          this.closeDialog();
        }
      } catch (error) {
        console.error('Error al guardar:', error);
      }
    }
  }
};
</script>

<style scoped>
.page-header {
  margin-bottom: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-content h2 {
  color: var(--primary-color);
  margin: 0;
}

.btn-add {
  background-color: var(--secondary-color);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-add:hover {
  background-color: var(--primary-color);
}

.search-box {
  position: relative;
  max-width: 300px;
}

.search-box i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-color);
  opacity: 0.5;
}

.search-box input {
  padding-left: 35px;
  width: 100%;
  border-radius: 4px;
  border: 1px solid #ddd;
  padding: 0.5rem 0.5rem 0.5rem 35px;
}

.loading-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 2rem;
  background: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
}

.loading-container i {
  font-size: 1.5rem;
  color: var(--secondary-color);
}
</style>