<template>
  <div>
    <h2>Vehículos Retirados</h2>
    <div class="filters">
      <input v-model="filtroMatricula" placeholder="Filtrar por matrícula" />
      <input v-model="filtroEstado" placeholder="Filtrar por estado" />
    </div>
    <table>
      <thead>
        <tr>
          <th>Matrícula</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="vehiculo in vehiculosFiltrados" :key="vehiculo.id">
          <td>{{ vehiculo.matricula }}</td>
          <td>{{ vehiculo.marca }}</td>
          <td>{{ vehiculo.modelo }}</td>
          <td>{{ vehiculo.estado }}</td>
          <td>
            <button @click="editarVehiculo(vehiculo)">Editar</button>
            <button @click="eliminarVehiculo(vehiculo.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import apiClient from '@/services/api';

export default {
  data() {
    return {
      vehiculos: [],
      filtroMatricula: '',
      filtroEstado: '',
    };
  },
  async created() {
    const response = await apiClient.get('/vehiculos');
    this.vehiculos = response.data;
  },
  computed: {
    vehiculosFiltrados() {
      return this.vehiculos.filter(
        (v) =>
          (!this.filtroMatricula || v.matricula.includes(this.filtroMatricula)) &&
          (!this.filtroEstado || v.estado === this.filtroEstado)
      );
    },
  },
  methods: {
    editarVehiculo(vehiculo) {
      this.$router.push(`/vehiculos/editar/${vehiculo.id}`);
    },
    async eliminarVehiculo(id) {
      await apiClient.delete(`/vehiculos/${id}`);
      this.vehiculos = this.vehiculos.filter((v) => v.id !== id);
    },
  },
};
</script>

<style scoped>
.filters {
  margin-bottom: 20px;
}
.filters input {
  margin-right: 10px;
}
</style>