<template>
  <div>
    <!-- Campo de Filtro -->
    <input
      type="text"
      v-model="filtro"
      placeholder="Filtrar por matrícula..."
      class="form-control mb-3"
    />

    <!-- Lista de Vehículos Filtrados -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Matrícula</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Color</th>
          <th>Tipo de Vehículo</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="vehiculo in vehiculosFiltrados" :key="vehiculo.id">
          <td>{{ vehiculo.matricula }}</td>
          <td>{{ vehiculo.marca }}</td>
          <td>{{ vehiculo.modelo }}</td>
          <td>{{ vehiculo.color }}</td>
          <td>{{ vehiculo.tipovehiculo }}</td>
          <td>{{ vehiculo.estado }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import apiClient from '@/services/api'; // Importa tu cliente Axios

export default {
  data() {
    return {
      vehiculos: [], // Inicializa como un array vacío
      filtro: '' // Variable para almacenar el filtro
    };
  },
  computed: {
    vehiculosFiltrados() {
      // Verifica que this.vehiculos sea un array antes de usar .filter()
      if (!Array.isArray(this.vehiculos)) {
        console.error('this.vehiculos no es un array:', this.vehiculos);
        return [];
      }

      // Filtra los vehículos por matrícula (insensible a mayúsculas/minúsculas)
      return this.vehiculos.filter((vehiculo) => {
        return vehiculo.matricula
          .toLowerCase()
          .includes(this.filtro.toLowerCase());
      });
    }
  },
  methods: {
    async cargarVehiculos() {
      try {
        const response = await apiClient.get('/vehiculos'); // Solicita los vehículos al backend
        this.vehiculos = response.data; // Asigna los datos recibidos a this.vehiculos

        // Valida que la respuesta sea un array
        if (!Array.isArray(this.vehiculos)) {
          console.error(
            'La respuesta de la API no es un array:',
            this.vehiculos
          );
          this.vehiculos = []; // Asegura que this.vehiculos sea un array
        }
      } catch (error) {
        console.error('Error al cargar vehículos:', error);
        this.vehiculos = []; // En caso de error, inicializa como un array vacío
      }
    }
  },
  mounted() {
    this.cargarVehiculos(); // Carga los vehículos al montar el componente
  }
};
</script>

<style scoped>
/* Estilos opcionales */
input {
  width: 100%;
  padding: 8px;
  margin-bottom: 16px;
}
</style>