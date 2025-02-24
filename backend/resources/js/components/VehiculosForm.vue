<template>
  <Dialog :visible="visible" :header="dialogHeader" modal class="p-fluid"
    @update:visible="$emit('update:visible', $event)">
    <div class="form-group">
      <label for="matricula">Matrícula</label>
      <InputText id="matricula" v-model="formData.matricula" type="text" class="form-control" required />
      <small v-if="!formData.matricula" class="p-error">La matrícula es obligatoria.</small>
    </div>

    <div class="form-group">
      <label for="marca">Marca</label>
      <Dropdown id="marca" v-model="formData.marca" :options="marcas" optionLabel="make_display"
        placeholder="Selecciona una marca" @change="cargarModelosPorMarca" class="form-control" required />
      <small v-if="!formData.marca" class="p-error">La marca es obligatoria.</small>
    </div>

    <div class="form-group">
      <label for="modelo">Modelo</label>
      <Dropdown id="modelo" v-model="formData.modelo" :options="modelos" optionLabel="model_name"
        placeholder="Selecciona un modelo" class="form-control" required />
      <small v-if="!formData.modelo" class="p-error">El modelo es obligatorio.</small>
    </div>

    <div class="form-group">
      <label for="color">Color</label>
      <input id="color" v-model="formData.color" type="color" class="form-control" required />
    </div>

    <div class="form-group">
      <label for="tipo">Tipo de Vehículo</label>
      <Dropdown id="tipo" v-model="formData.tipovehiculo" :options="tiposVehiculo" optionLabel="name"
        placeholder="Selecciona un tipo" class="form-control" required />
      <small v-if="!formData.tipovehiculo" class="p-error">El tipo de vehículo es obligatorio.</small>
    </div>

    <div class="form-group">
      <label for="estado">Estado</label>
      <Dropdown id="estado" v-model="formData.estado" :options="estadosVehiculo" optionLabel="name"
        placeholder="Selecciona un estado" class="form-control" required />
      <small v-if="!formData.estado" class="p-error">El estado es obligatorio.</small>
    </div>

    <template #footer>
      <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="onClose" />
      <Button label="Guardar" icon="pi pi-check" class="p-button-primary" @click="onSave" />
    </template>
  </Dialog>
</template>

<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import axios from "axios";

export default {
  name: 'VehiculoForm',
  components: {
    Dialog,
    Button,
    InputText,
    Dropdown
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
      marcas: [],
      modelos: [],
      tiposVehiculo: [
        { name: 'Turismo' },
        { name: 'SUV' },
        { name: 'Motocicleta' },
        { name: 'Furgoneta' },
        { name: 'Camión' }
      ],
      estadosVehiculo: [
        { name: 'Activo' },
        { name: 'Inactivo' }
      ]
    };
  },
  watch: {
    vehiculo: {
      handler(newVal) {
        this.formData = { ...newVal };
      },
      deep: true
    }
  },
  created() {
    this.cargarMarcas();
  },
  methods: {
    async cargarMarcas() {
      try {
        const response = await axios.get("http://www.carqueryapi.com/api/0.3/?callback=?&cmd=getMakes");
        this.marcas = response.data.Makes;
      } catch (error) {
        console.error("Error al cargar las marcas:", error);
      }
    },
    async cargarModelosPorMarca() {
      if (!this.formData.marca) return;
      try {
        const makeId = this.formData.marca.make_id;
        const response = await axios.get(
          `http://www.carqueryapi.com/api/0.3/?callback=?&cmd=getModels&make=${makeId}`
        );
        this.modelos = response.data.Models;
      } catch (error) {
        console.error("Error al cargar los modelos:", error);
      }
    },
    validateForm() {
      return (
        this.formData.matricula &&
        this.formData.marca &&
        this.formData.modelo &&
        this.formData.tipovehiculo &&
        this.formData.estado
      );
    },
    onSave() {
      if (!this.validateForm()) {
        alert("Por favor, complete todos los campos obligatorios.");
        return;
      }
      this.$emit('save', this.formData);
    },
    onClose() {
      this.$emit('close');
    }
  }
};
</script>

<style scoped>
.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--primary-color);
  font-weight: 500;
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

.p-error {
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 0.25rem;
  display: block;
}
</style>
