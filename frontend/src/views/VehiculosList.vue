<template>
  <div class="container">
    <!-- Campo de Filtro -->
    <div class="filter-container">
      <input
        type="text"
        v-model="filtro"
        placeholder="Filtrar por matrícula..."
        class="form-control"
      />
    </div>

    <!-- Lista de Vehículos Filtrados -->
    <div class="table-container">
      <table class="custom-table">
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
          <tr v-if="vehiculosFiltrados.length === 0">
            <td colspan="6" class="no-results">No se encontraron resultados</td>
          </tr>
        </tbody>
      </table>
    </div>
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
/* Estilos Generales */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  color: #333333;
}

/* Estilos del campo de filtro */
.filter-container {
  margin-bottom: 20px;
}

.form-control {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  border: 1px solid #cccccc;
  border-radius: 8px;
  transition: border-color 0.3s ease;
}

.form-control:focus {
  border-color: #007bff;
  outline: none;
}

/* Estilos de la tabla */
.table-container {
  overflow-x: auto;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.custom-table thead {
  background-color: #f8f9fa;
}

.custom-table th,
.custom-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #dddddd;
}

.custom-table th {
  font-weight: 600;
  color: #333333;
}

.custom-table tbody tr:hover {
  background-color: #f1f1f1;
}

.no-results {
  text-align: center;
  color: #6c757d;
  font-style: italic;
}
</style>