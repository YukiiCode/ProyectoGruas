<template>
    <div class="retiradas-list">
      <!-- Encabezado -->
      <h2>Gestión de Retiradas</h2>
  
      <!-- Filtro -->
      <div class="filters">
        <input
          type="text"
          v-model="filtroMatricula"
          placeholder="Filtrar por matrícula..."
          class="form-control"
        />
      </div>
  
      <!-- Tabla de Retiradas -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Matrícula</th>
            <th>Nombre</th>
            <th>NIF</th>
            <th>Fecha</th>
            <th>Importe Retirada</th>
            <th>Importe Depósito</th>
            <th>Total</th>
            <th>Método de Pago</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="retirada in retiradasFiltradas" :key="retirada.Id">
            <td>{{ retirada.Id }}</td>
            <td>{{ retirada.idvehiculos }}</td>
            <td>{{ retirada.nombre }}</td>
            <td>{{ retirada.nif }}</td>
            <td>{{ formatDate(retirada.fecha) }}</td>
            <td>{{ retirada.importeretirada }} €</td>
            <td>{{ retirada.importedeposito }} €</td>
            <td>{{ retirada.total }} €</td>
            <td>{{ retirada.opcionespago }}</td>
            <td>
              <button @click="generarRecibo(retirada)" class="btn btn-primary">
                Generar Recibo
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import apiClient from "@/services/api"; // Importa tu cliente Axios
  
  export default {
    data() {
      return {
        retiradas: [], // Lista de retiradas cargadas desde la API
        filtroMatricula: "", // Filtro por matrícula
      };
    },
    computed: {
      retiradasFiltradas() {
        if (!Array.isArray(this.retiradas)) {
          console.error("this.retiradas no es un array:", this.retiradas);
          return [];
        }
  
        // Filtra las retiradas por matrícula (insensible a mayúsculas/minúsculas)
        return this.retiradas.filter((retirada) => {
          return retirada.idvehiculos
            .toLowerCase()
            .includes(this.filtroMatricula.toLowerCase());
        });
      },
    },
    methods: {
      async cargarRetiradas() {
        try {
          const response = await apiClient.get("/retiradas");
          this.retiradas = response.data;
  
          // Valida que la respuesta sea un array
          if (!Array.isArray(this.retiradas)) {
            console.error(
              "La respuesta de la API no es un array:",
              this.retiradas
            );
            this.retiradas = [];
          }
        } catch (error) {
          console.error("Error al cargar retiradas:", error);
          this.retiradas = [];
        }
      },
      formatDate(dateString) {
        // Formatea la fecha para mostrarla en formato legible
        const date = new Date(dateString);
        return date.toLocaleDateString("es-ES", {
          day: "2-digit",
          month: "2-digit",
          year: "numeric",
          hour: "2-digit",
          minute: "2-digit",
        });
      },
      generarRecibo(retirada) {
        // Simula la generación de un recibo
        alert(
          `Recibo generado:\n\n` +
            `ID: ${retirada.Id}\n` +
            `Matrícula: ${retirada.idvehiculos}\n` +
            `Nombre: ${retirada.nombre}\n` +
            `NIF: ${retirada.nif}\n` +
            `Total: ${retirada.total} €\n` +
            `Método de Pago: ${retirada.opcionespago}`
        );
      },
    },
    mounted() {
      this.cargarRetiradas(); // Carga las retiradas al montar el componente
    },
  };
  </script>
  
  <style scoped>
  /* Estilos opcionales */
  .retiradas-list {
    margin: 20px;
  }
  
  .filters {
    margin-bottom: 20px;
  }
  
  input.form-control {
    width: 100%;
    padding: 8px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th,
  td {
    padding: 10px;
    text-align: center;
  }
  
  button.btn {
    padding: 5px 10px;
    cursor: pointer;
  }
  </style>