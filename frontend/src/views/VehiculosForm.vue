<template>
    <div>
      <h2>{{ esEdicion ? 'Editar Vehículo' : 'Registrar Vehículo' }}</h2>
      <form @submit.prevent="guardarVehiculo">
        <div>
          <label>Matrícula:</label>
          <input v-model="vehiculo.matricula" required />
        </div>
        <div>
          <label>Marca:</label>
          <input v-model="vehiculo.marca" required />
        </div>
        <div>
          <label>Modelo:</label>
          <input v-model="vehiculo.modelo" required />
        </div>
        <div>
          <label>Estado:</label>
          <select v-model="vehiculo.estado">
            <option value="En depósito">En depósito</option>
            <option value="Liquidado">Liquidado</option>
          </select>
        </div>
        <button type="submit">{{ esEdicion ? 'Actualizar' : 'Guardar' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import apiClient from '@/services/api';
  
  export default {
    props: ['id'],
    data() {
      return {
        vehiculo: {
          matricula: '',
          marca: '',
          modelo: '',
          estado: 'En depósito',
        },
      };
    },
    computed: {
      esEdicion() {
        return !!this.id;
      },
    },
    async created() {
      if (this.esEdicion) {
        const response = await apiClient.get(`/vehiculos/${this.id}`);
        this.vehiculo = response.data;
      }
    },
    methods: {
      async guardarVehiculo() {
        if (this.esEdicion) {
          await apiClient.put(`/vehiculos/${this.id}`, this.vehiculo);
        } else {
          await apiClient.post('/vehiculos', this.vehiculo);
        }
        this.$router.push('/vehiculos');
      },
    },
  };
  </script>