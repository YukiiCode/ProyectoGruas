<template>
  <Dialog :visible="visible" :header="dialogHeader" modal class="p-fluid vehicle-dialog"
    :style="{ width: '80vw', maxWidth: '900px' }" @update:visible="$emit('update:visible', $event)">
    <div class="form-grid">
      <div class="form-group">
        <label for="fechaentrada">Fecha de Entrada</label>
        <Calendar id="fechaentrada" v-model="formData.fechaentrada" :showTime="true" dateFormat="dd/mm/yy"
          class="form-control" required />
        <small v-if="!formData.fechaentrada && formSubmitted" class="p-error">La fecha de entrada es
          obligatoria.</small>
      </div>

      <div class="form-group">
        <label for="fechasalida">Fecha de Salida</label>
        <Calendar id="fechasalida" v-model="formData.fechasalida" :showTime="true" dateFormat="dd/mm/yy"
          class="form-control" />
      </div>

      <div class="form-group">
        <label for="lugar">Lugar</label>
        <InputText id="lugar" v-model="formData.lugar" type="text" class="form-control" required />
        <small v-if="!formData.lugar && formSubmitted" class="p-error">El lugar es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="direccion">Dirección</label>
        <InputText id="direccion" v-model="formData.direccion" type="text" class="form-control" required />
        <small v-if="!formData.direccion && formSubmitted" class="p-error">La dirección es obligatoria.</small>
      </div>

      <div class="form-group">
        <label for="agente">Agente</label>
        <InputText id="agente" v-model="formData.agente" type="text" class="form-control" required readonly disabled />
        <small v-if="!formData.agente && formSubmitted" class="p-error">El agente es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="matricula">Matrícula</label>
        <InputText id="matricula" v-model="formData.matricula" type="text" class="form-control" required />
        <small v-if="!formData.matricula && formSubmitted" class="p-error">La matrícula es obligatoria.</small>
      </div>

      <div class="form-group">
        <label for="marca">Marca</label>
        <AutoComplete id="marca" v-model="formData.marca" :suggestions="filteredMarcas" optionLabel="make_display"
          placeholder="Buscar una marca" @complete="searchMarcas" @item-select="cargarModelosPorMarca"
          class="form-control" required />
        <small v-if="!formData.marca && formSubmitted" class="p-error">La marca es obligatoria.</small>
      </div>

      <div class="form-group">
        <label for="modelo">Modelo</label>
        <AutoComplete id="modelo" v-model="formData.modelo" :suggestions="filteredModelos" optionLabel="model_name"
          placeholder="Buscar un modelo" @complete="searchModelos" class="form-control" required />
        <small v-if="!formData.modelo && formSubmitted" class="p-error">El modelo es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="color">Color</label>
        <Dropdown id="color" v-model="formData.color" :options="colores" optionLabel="name"
          placeholder="Selecciona un color" class="form-control" required />
        <small v-if="!formData.color && formSubmitted" class="p-error">El color es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="tipo">Tipo de Vehículo</label>
        <Dropdown id="tipo" v-model="formData.tipovehiculo" :options="tiposVehiculo" optionLabel="name"
          placeholder="Selecciona un tipo" class="form-control" required />
        <small v-if="!formData.tipovehiculo && formSubmitted" class="p-error">El tipo de vehículo es
          obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="estado">Estado</label>
        <Dropdown id="estado" v-model="formData.estado" :options="estadosVehiculo" optionLabel="name"
          placeholder="Selecciona un estado" class="form-control" required />
        <small v-if="!formData.estado && formSubmitted" class="p-error">El estado es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="motivo">Motivo</label>
        <InputText id="motivo" v-model="formData.motivo" type="text" class="form-control" required />
        <small v-if="!formData.motivo && formSubmitted" class="p-error">El motivo es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="grua">Grúa</label>
        <Dropdown id="grua" v-model="formData.grua" :options="gruas" optionLabel="name" placeholder="Selecciona una grúa" class="form-control" required />
        <small v-if="!formData.grua && formSubmitted" class="p-error">La grúa es obligatoria.</small>
      </div>
    </div>

    <template #footer>
      <div class="dialog-footer">
        <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="onClose" />
        <Button label="Guardar" icon="pi pi-check" class="p-button-primary" @click="onSave" />
      </div>
    </template>
  </Dialog>
</template>

<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import axios from "axios";

export default {
  name: 'VehiculoForm',
  components: {
    Dialog,
    Button,
    InputText,
    Dropdown,
    AutoComplete,
    Calendar
  },
  props: {
    visible: {
      type: Boolean,
      required: true
    },
    vehiculo: {
      type: Object,
      required: true
    },
    dialogHeader: {
      type: String,
      default: 'Vehículo'
    }
  },
  emits: ['update:visible', 'save', 'close'],
  data() {
    return {
      formData: { ...this.vehiculo },
      formSubmitted: false,
      marcas: [],
      filteredMarcas: [],
      modelos: [],
      filteredModelos: [],
      gruas: [
        { name: 'Grua01' },
        { name: 'Grua02' },
        { name: 'Grua03' },
        { name: 'Grua04' },
        { name: 'Grua05' },
        { name: 'Grua06' }
      ],
      colores: [
        { name: 'Blanco', value: '#FFFFFF' },
        { name: 'Negro', value: '#000000' },
        { name: 'Gris', value: '#808080' },
        { name: 'Plata', value: '#C0C0C0' },
        { name: 'Rojo', value: '#FF0000' },
        { name: 'Azul', value: '#0000FF' },
        { name: 'Verde', value: '#008000' },
        { name: 'Amarillo', value: '#FFFF00' },
        { name: 'Naranja', value: '#FFA500' },
        { name: 'Marrón', value: '#A52A2A' },
        { name: 'Beige', value: '#F5F5DC' },
        { name: 'Granate', value: '#800000' },
        { name: 'Azul marino', value: '#000080' },
        { name: 'Verde oscuro', value: '#006400' }
      ],
      tiposVehiculo: [
        { name: 'Motocicleta, aperos, motocarros y similares', value: 'Motocicleta_aperos_motocarros_y_similares', precio: 25 },
        { name: 'Turismo hasta 12 cv ó Remolques hasta 750 kg', value: 'Turismo_hasta_12cv', precio: 100 },
        { name: 'Turismos más de 12 cv ó Remolques más de 750 kg', value: 'Turismo_mas_12cv', precio: 130 },
        { name: 'Vehículos especiales', value: 'Vehiculos_especiales', precio: 150 },
        { name: 'Vehículos de cortesía', value: 'Vehiculos_de_cortesia', precio: 0 },
        { name: 'Chatarra', value: 'Chatarra', precio: 0 }
      ],
      estadosVehiculo: [
        { name: 'En depósito', value: 'En depósito' },
        { name: 'Liquidado', value: 'Liquidado' }
      ] 
    };
  },
  watch: {
    vehiculo: {
      handler(newVal) {
        const formDataCopy = { ...newVal };

        // Handle color dropdown
        if (typeof formDataCopy.color === 'string') {
          const colorObj = this.colores.find(c => c.name === formDataCopy.color || c.value === formDataCopy.color);
          formDataCopy.color = colorObj || null;
        }

        // Handle tipovehiculo dropdown
        if (typeof formDataCopy.tipovehiculo === 'string') {
          // First try to find by value (database format)
          const tipoObj = this.tiposVehiculo.find(t => t.value === formDataCopy.tipovehiculo);
          if (tipoObj) {
            formDataCopy.tipovehiculo = tipoObj;
          } else {
            // If not found by value, try to find by name
            const tipoByName = this.tiposVehiculo.find(t => t.name === formDataCopy.tipovehiculo);
            if (tipoByName) {
              formDataCopy.tipovehiculo = tipoByName;
            } else {
              // If still not found, create a custom option
              const customTipo = { name: formDataCopy.tipovehiculo, value: formDataCopy.tipovehiculo, precio: 0 };
              this.tiposVehiculo.push(customTipo);
              formDataCopy.tipovehiculo = customTipo;
            }
          }
        }

        // Handle estado dropdown
        if (typeof formDataCopy.estado === 'string') {
          const estadoObj = this.estadosVehiculo.find(e => e.name === formDataCopy.estado);
          formDataCopy.estado = estadoObj || null;
        }

        // Handle grua dropdown
        if (typeof formDataCopy.grua === 'string') {
          const gruaObj = this.gruas.find(g => g.name === formDataCopy.grua);
          formDataCopy.grua = gruaObj || null;
        }

        this.formData = formDataCopy;
      },
      deep: true
    }
  },
  created() {
    this.cargarMarcas();
    this.cargarUsuarioActual();
  },
  methods: {
    async cargarMarcas() {
      try {
        const response = await axios.get("/api/car-makes", {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        if (response.data && response.data.Makes) {
          this.marcas = response.data.Makes;
        } else {
          console.error("Error: Formato de respuesta inválido");
          this.$toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudieron cargar las marcas de vehículos. Por favor, inténtelo de nuevo más tarde.' });
        }
      } catch (error) {
        console.error("Error al cargar las marcas:", error);
        if (error.response && error.response.status === 401) {
          this.$toast.add({ severity: 'error', summary: 'Error de autenticación', detail: 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.' });
          // Redirect to login if needed
          setTimeout(() => {
            window.location.href = '/login';
          }, 2000);
        } else {
          const errorMessage = error.response?.data?.error || 'No se pudieron cargar las marcas de vehículos';
          this.$toast.add({ severity: 'error', summary: 'Error', detail: errorMessage });
        }
      }
    },
    searchMarcas(event) {
      const query = event.query.toLowerCase();
      this.filteredMarcas = this.marcas.filter(marca =>
        marca.make_display.toLowerCase().includes(query)
      );
    },
    searchModelos(event) {
      const query = event.query.toLowerCase();
      this.filteredModelos = this.modelos.filter(modelo =>
        modelo.model_name.toLowerCase().includes(query)
      );
    },
    async cargarModelosPorMarca() {
      if (!this.formData.marca) return;
      try {
        const makeId = this.formData.marca.make_id;
        const response = await axios.get(`/api/car-models`, {
          params: { make: makeId },
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        if (response.data && response.data.Models) {
          this.modelos = response.data.Models;
        } else {
          console.error("Error: Formato de respuesta inválido");
          this.$toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudieron cargar los modelos. Por favor, inténtelo de nuevo más tarde.' });
        }
      } catch (error) {
        console.error("Error al cargar los modelos:", error);
        if (error.response && error.response.status === 401) {
          this.$toast.add({ severity: 'error', summary: 'Error de autenticación', detail: 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.' });
          // Redirect to login if needed
          setTimeout(() => {
            window.location.href = '/login';
          }, 2000);
        } else {
          const errorMessage = error.response?.data?.error || 'No se pudieron cargar los modelos';
          this.$toast.add({ severity: 'error', summary: 'Error', detail: errorMessage });
        }
      }
    },
    async cargarUsuarioActual() {
      try {
        const response = await axios.get('/api/user', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.formData.agente = response.data.nombre;
      } catch (error) {
        console.error('Error al cargar el usuario:', error);
        if (error.response && error.response.status === 401) {
          this.$toast.add({ severity: 'error', summary: 'Error de autenticación', detail: 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.' });
          // Redirect to login if needed
          setTimeout(() => {
            window.location.href = '/login';
          }, 2000);
        } else {
          this.$toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo cargar la información del usuario' });
        }
      }
    },
    validateForm() {
      return (
        this.formData.fechaentrada &&
        this.formData.lugar &&
        this.formData.direccion &&
        this.formData.agente &&
        this.formData.matricula &&
        this.formData.marca &&
        this.formData.modelo &&
        this.formData.tipovehiculo &&
        this.formData.estado &&
        this.formData.motivo &&
        this.formData.grua
      );
    },
    onSave() {
      this.formSubmitted = true;
      if (!this.validateForm()) {
        alert("Por favor, complete todos los campos obligatorios.");
        return;
      }

      const saveData = { ...this.formData };

      if (saveData.color && typeof saveData.color === 'object') {
        saveData.color = saveData.color.name;
      }
      
      if (saveData.grua && typeof saveData.grua === 'object') {
        saveData.grua = saveData.grua.name;
      }

      if (saveData.marca && typeof saveData.marca === 'object') {
        saveData.marca = saveData.marca.make_display;
      }

      if (saveData.modelo && typeof saveData.modelo === 'object') {
        saveData.modelo = saveData.modelo.model_name;
      }

      if (saveData.tipovehiculo && typeof saveData.tipovehiculo === 'object') {
        saveData.tipovehiculo = saveData.tipovehiculo.value;
      }

      if (saveData.estado && typeof saveData.estado === 'object') {
        saveData.estado = saveData.estado.name;
      }

      this.$emit('save', saveData);
    },
    onClose() {
      this.$emit('close');
    }
  }
};
</script>

<style scoped>
.vehicle-dialog {
  min-width: 60vw;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
  padding: 1rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--text-color);
  font-weight: 600;
  font-size: 1rem;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.1);
  outline: none;
}

.color-picker-wrapper {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.color-picker {
  width: 100px;
  height: 40px;
  padding: 0;
  border: 1px solid #ddd;
  border-radius: 6px;
  cursor: pointer;
}

.color-value {
  font-family: monospace;
  color: var(--text-color);
}

.dialog-footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.p-error {
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 0.25rem;
  display: block;
}

:deep(.p-dropdown) {
  width: 100%;
}

:deep(.p-inputtext) {
  width: 100%;
}

:deep(.p-autocomplete) {
  width: 100%;
}
</style>
