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
          <input 
            v-model="filtro" 
            type="text" 
            placeholder="Buscar por matrícula..." 
            class="form-control"
          />
        </div>
      </div>
    </div>

    <div class="table-container" v-if="!loading">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Matrícula</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Color</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="vehiculo in vehiculosFiltrados" :key="vehiculo.id">
            <td><span class="matricula-badge">{{ vehiculo.matricula }}</span></td>
            <td>{{ vehiculo.marca }}</td>
            <td>{{ vehiculo.modelo }}</td>
            <td><span class="color-dot" :style="{ backgroundColor: vehiculo.color }"></span>{{ vehiculo.color }}</td>
            <td><span class="tipo-badge">{{ vehiculo.tipovehiculo }}</span></td>
            <td>
              <span :class="['status-badge', vehiculo.estado === 'Activo' ? 'status-active' : 'status-inactive']">
                {{ vehiculo.estado }}
              </span>
            </td>
            <td class="actions">
              <button class="btn-action edit" @click="editVehiculo(vehiculo)">
                <i class="pi pi-pencil"></i>
              </button>
              <button class="btn-action delete" @click="deleteVehiculo(vehiculo)">
                <i class="pi pi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="loading-container">
      <i class="pi pi-spin pi-spinner"></i>
      <span>Cargando vehículos...</span>
    </div>

    <!-- Dialog para añadir/editar vehículo -->
    <Dialog v-model:visible="dialogVisible" :header="dialogHeader" modal class="p-fluid">
      <div class="form-group">
        <label for="matricula">Matrícula</label>
        <input id="matricula" v-model="vehiculoForm.matricula" type="text" class="form-control" required />
      </div>
      <div class="form-group">
        <label for="marca">Marca</label>
        <input id="marca" v-model="vehiculoForm.marca" type="text" class="form-control" required />
      </div>
      <div class="form-group">
        <label for="modelo">Modelo</label>
        <input id="modelo" v-model="vehiculoForm.modelo" type="text" class="form-control" required />
      </div>
      <div class="form-group">
        <label for="color">Color</label>
        <input id="color" v-model="vehiculoForm.color" type="color" class="form-control" required />
      </div>
      <div class="form-group">
        <label for="tipo">Tipo de Vehículo</label>
        <select id="tipo" v-model="vehiculoForm.tipovehiculo" class="form-control" required>
          <option value="Turismo">Turismo</option>
          <option value="Furgoneta">Furgoneta</option>
          <option value="Camión">Camión</option>
          <option value="Motocicleta">Motocicleta</option>
        </select>
      </div>
      <div class="form-group">
        <label for="estado">Estado</label>
        <select id="estado" v-model="vehiculoForm.estado" class="form-control" required>
          <option value="Activo">Activo</option>
          <option value="Inactivo">Inactivo</option>
        </select>
      </div>
      <template #footer>
        <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="closeDialog" />
        <Button label="Guardar" icon="pi pi-check" class="p-button-text" @click="saveVehiculo" />
      </template>
    </Dialog>

    <!-- Dialog de confirmación para eliminar -->
    <Dialog v-model:visible="deleteDialogVisible" header="Confirmar Eliminación" modal>
      <p>¿Está seguro que desea eliminar este vehículo?</p>
      <template #footer>
        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteDialogVisible = false" />
        <Button label="Sí" icon="pi pi-check" class="p-button-text p-button-danger" @click="confirmDelete" />
      </template>
    </Dialog>
  </div>
</template>

<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';

export default {
  name: 'Vehiculos',
  components: {
    Dialog,
    Button
  },
  data() {
    return {
      vehiculos: [],
      filtro: '',
      loading: false,
      dialogVisible: false,
      deleteDialogVisible: false,
      dialogHeader: 'Nuevo Vehículo',
      vehiculoForm: this.getEmptyVehiculoForm(),
      vehiculoToDelete: null
    }
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
        marca: '',
        modelo: '',
        color: '#000000',
        tipovehiculo: 'Turismo',
        estado: 'Activo'
      }
    },
    async cargarVehiculos() {
      this.loading = true;
      try {
        const response = await fetch('/api/vehiculos', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        const data = await response.json();
        this.vehiculos = data;
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
      this.vehiculoForm = this.getEmptyVehiculoForm();
    },
    async saveVehiculo() {
      try {
        const method = this.vehiculoForm.id ? 'PUT' : 'POST';
        const url = this.vehiculoForm.id ? `/api/vehiculos/${this.vehiculoForm.id}` : '/api/vehiculos';
        
        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify(this.vehiculoForm)
        });

        if (!response.ok) throw new Error('Error al guardar el vehículo');
        
        await this.cargarVehiculos();
        this.closeDialog();
      } catch (error) {
        console.error('Error:', error);
      }
    },
    editVehiculo(vehiculo) {
      this.openDialog(vehiculo);
    },
    deleteVehiculo(vehiculo) {
      this.vehiculoToDelete = vehiculo;
      this.deleteDialogVisible = true;
    },
    async confirmDelete() {
      if (!this.vehiculoToDelete) return;
      
      try {
        const response = await fetch(`/api/vehiculos/${this.vehiculoToDelete.id}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });

        if (!response.ok) throw new Error('Error al eliminar el vehículo');
        
        await this.cargarVehiculos();
        this.deleteDialogVisible = false;
        this.vehiculoToDelete = null;
      } catch (error) {
        console.error('Error:', error);
      }
    }
  }
}
</script>

<style scoped>
.page-header {
  margin-bottom: 2rem;
}

.page-header h2 {
  color: var(--primary-color);
  margin-bottom: 1rem;
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
}

.table-container {
  background: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
  overflow: hidden;
}

.matricula-badge {
  background-color: var(--primary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-family: monospace;
  font-size: 0.9em;
}

.color-dot {
  display: inline-block;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  margin-right: 8px;
  border: 1px solid rgba(0,0,0,0.1);
}

.tipo-badge {
  background-color: var(--secondary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.85em;
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

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
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

.actions {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
}

.btn-action {
  border: none;
  border-radius: 4px;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-action.edit {
  background-color: var(--secondary-color);
  color: white;
}

.btn-action.delete {
  background-color: #dc3545;
  color: white;
}

.btn-action:hover {
  opacity: 0.8;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--primary-color);
}

.form-control {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-control:focus {
  border-color: var(--secondary-color);
  outline: none;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.85em;
}

.status-active {
  background-color: #28a745;
  color: white;
}

.status-inactive {
  background-color: #dc3545;
  color: white;
}
</style>