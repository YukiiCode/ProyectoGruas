<template>
  <div class="nueva-retirada-container">
    <h2>Nueva Retirada de Vehículo</h2>
    <form @submit.prevent="registrarRetirada" class="retirada-form">
      <!-- Vehicle Registration Form -->
      <div class="form-group">
        <label for="marca">Marca</label>
        <select id="marca" v-model="selectedMarca" required class="form-control" @change="loadModelos">
          <option value="">Seleccione una marca</option>
          <option v-for="marca in marcas" :key="marca" :value="marca">{{ marca }}</option>
        </select>
      </div>

      <div class="form-group">
        <label for="modelo">Modelo</label>
        <select id="modelo" v-model="selectedModelo" required class="form-control" :disabled="!selectedMarca">
          <option value="">Seleccione un modelo</option>
          <option v-for="modelo in modelos" :key="modelo" :value="modelo">{{ modelo }}</option>
        </select>
      </div>

      <div class="form-group">
        <label for="color">Color</label>
        <select id="color" v-model="selectedColor" required class="form-control">
          <option value="">Seleccione un color</option>
          <option v-for="color in colores" :key="color.value" :value="color.value">
            {{ color.label }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="nombre">Nombre del Propietario</label>
        <input
          id="nombre"
          v-model="retirada.nombre"
          type="text"
          required
          class="form-control"
          placeholder="Nombre completo"
        />
      </div>

      <div class="form-group">
        <label for="nif">NIF/NIE</label>
        <input
          id="nif"
          v-model="retirada.nif"
          type="text"
          required
          class="form-control"
          placeholder="NIF/NIE del propietario"
        />
      </div>

      <div class="form-group">
        <label for="fecha">Fecha de Retirada</label>
        <input
          id="fecha"
          v-model="retirada.fecha"
          type="datetime-local"
          required
          class="form-control"
        />
      </div>

      <div class="form-group">
        <label for="motivo">Motivo de la Retirada</label>
        <textarea
          id="motivo"
          v-model="retirada.motivo"
          required
          class="form-control"
          rows="3"
          placeholder="Describa el motivo de la retirada"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="ubicacion">Ubicación de Retirada</label>
        <input
          id="ubicacion"
          v-model="retirada.ubicacion"
          type="text"
          required
          class="form-control"
          placeholder="Dirección donde se realizó la retirada"
        />
      </div>

      <div class="form-group">
        <label for="total">Importe Total (€)</label>
        <input
          id="total"
          v-model.number="retirada.total"
          type="number"
          required
          class="form-control"
          min="0"
          step="0.01"
        />
      </div>

      <div class="form-group">
        <label for="opcionespago">Forma de Pago</label>
        <select id="opcionespago" v-model="retirada.opcionespago" required class="form-control">
          <option value="">Seleccione una forma de pago</option>
          <option value="Efectivo">Efectivo</option>
          <option value="Tarjeta">Tarjeta</option>
          <option value="Transferencia">Transferencia</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <i class="pi pi-save"></i>
          Registrar Retirada
        </button>
        <button type="button" class="btn btn-secondary" @click="$router.push('/retiradas')">
          <i class="pi pi-times"></i>
          Cancelar
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'NuevaRetirada',
  data() {
    return {
      retirada: {
        nombre: '',
        nif: '',
        fecha: '',
        motivo: '',
        ubicacion: '',
        total: 0,
        opcionespago: ''
      },
      selectedMarca: '',
      selectedModelo: '',
      selectedColor: '',
      marcas: [],
      modelos: [],
      colores: [
        { value: '#FFFFFF', label: 'Blanco' },
        { value: '#000000', label: 'Negro' },
        { value: '#FF0000', label: 'Rojo' },
        { value: '#0000FF', label: 'Azul' },
        { value: '#808080', label: 'Gris' },
        { value: '#FFFF00', label: 'Amarillo' },
        { value: '#00FF00', label: 'Verde' },
        { value: '#FFA500', label: 'Naranja' },
        { value: '#800080', label: 'Morado' },
        { value: '#A52A2A', label: 'Marrón' }
      ]
    };
  },
  async mounted() {
    await this.loadMarcas();
  },
  methods: {
    async loadMarcas() {
      try {
        const response = await axios.get('https://car-api.com/api/makes');
        this.marcas = response.data;
      } catch (error) {
        console.error('Error al cargar marcas:', error);
        // Fallback data in case API fails
        this.marcas = ['Audi', 'BMW', 'Ford', 'Honda', 'Mercedes', 'Toyota', 'Volkswagen'];
      }
    },
    async loadModelos() {
      if (!this.selectedMarca) {
        this.modelos = [];
        return;
      }
      try {
        const response = await axios.get(`https://car-api.com/api/models/${this.selectedMarca}`);
        this.modelos = response.data.map(modelo => modelo.name);
      } catch (error) {
        console.error('Error al cargar modelos:', error);
        // More realistic fallback data based on selected make
        const fallbackModelos = {
          'Audi': ['A3', 'A4', 'Q5', 'Q7'],
          'BMW': ['Serie 3', 'Serie 5', 'X3', 'X5'],
          'Ford': ['Focus', 'Fiesta', 'Kuga', 'Puma'],
          'Honda': ['Civic', 'CR-V', 'HR-V', 'Jazz'],
          'Mercedes': ['Clase A', 'Clase C', 'GLA', 'GLC'],
          'Toyota': ['Corolla', 'RAV4', 'Yaris', 'C-HR'],
          'Volkswagen': ['Golf', 'Polo', 'Tiguan', 'T-Roc']
        };
        this.modelos = fallbackModelos[this.selectedMarca] || ['Modelo no disponible'];
      }
    },
    async registrarRetirada() {
      if (!this.validateForm()) {
        return;
      }
      try {
        const vehiculoData = {
          marca: this.selectedMarca,
          modelo: this.selectedModelo,
          color: this.selectedColor
        };
        const vehiculoResponse = await axios.post('/api/vehiculos', vehiculoData, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        
        // Format the date to YYYY-MM-DD before sending
        const fecha = new Date(this.retirada.fecha);
        const formattedFecha = fecha.toISOString().split('T')[0];
        
        const retiradaData = {
          ...this.retirada,
          fecha: formattedFecha,
          vehiculo_id: vehiculoResponse.data.id
        };
        await axios.post('/api/retiradas', retiradaData, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        
        this.$router.push('/retiradas');
      } catch (error) {
        console.error('Error al registrar la retirada:', error);
        // Aquí podrías añadir una notificación de error para el usuario
      }
    },
    validateForm() {
      // Implementar validación del formulario
      if (!this.retirada.nombre || !this.retirada.nif || !this.retirada.fecha || !this.retirada.motivo || !this.retirada.ubicacion || !this.retirada.total || !this.retirada.opcionespago || !this.selectedMarca || !this.selectedModelo || !this.selectedColor) {
        alert('Por favor, complete todos los campos del formulario.');
        return false;
      }
      return true;
    }
  }
};
</script>

<style scoped>
.nueva-retirada-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.retirada-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: var(--shadow);
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 2rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-primary {
  background: var(--primary-color);
  color: white;
  border: none;
}

.btn-secondary {
  background: var(--secondary-color);
  color: white;
  border: none;
}

.btn i {
  font-size: 1.1em;
}
</style>