<template>
  <div class="container">
    <div class="page-header">
      <h2>Registro de Logs</h2>
      <div class="filter-container">
        <div class="search-box">
          <i class="pi pi-search"></i>
          <input v-model="filtro" type="text" placeholder="Buscar por usuario o acción..." class="form-control" />
          <button v-if="filtro" @click="filtro = ''" class="clear-search">
            <i class="pi pi-times"></i>
          </button>
        </div>
        <div class="type-filter">
          <Dropdown v-model="selectedType" :options="logTypes" optionLabel="label" placeholder="Filtrar por tipo" class="form-control" />
        </div>
      </div>
    </div>

    <div class="table-container" v-if="!loading">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Tipo</th>
            <th>Acción</th>
            <th>Detalles</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in paginatedLogs" :key="log.id">
            <td>{{ formatDate(log.created_at) }}</td>
            <td>{{ log.user_name }}</td>
            <td>
              <span :class="['log-type-badge', log.type.toLowerCase()]">{{ log.type }}</span>
            </td>
            <td>{{ log.accion }}</td>
            <td>{{ log.detalles }}</td>
          </tr>
        </tbody>
      </table>

      <div class="pagination" v-if="totalPages >= 1">
        <button 
          class="pagination-button" 
          :disabled="currentPage === 1" 
          @click="currentPage--"
        >
          <i class="pi pi-chevron-left"></i>
        </button>
        <button 
          v-for="page in totalPages" 
          :key="page" 
          class="pagination-button" 
          :class="{ active: currentPage === page }" 
          @click="currentPage = page"
        >
          {{ page }}
        </button>
        <button 
          class="pagination-button" 
          :disabled="currentPage === totalPages" 
          @click="currentPage++"
        >
          <i class="pi pi-chevron-right"></i>
        </button>
      </div>
    </div>

    <div v-else class="loading-container">
      <i class="pi pi-spin pi-spinner"></i>
      <span>Cargando logs...</span>
    </div>
  </div>
</template>

<script>
import Dropdown from 'primevue/dropdown';

export default {
  name: 'Logs',
  components: {
    Dropdown
  },
  data() {
    return {
      logs: [],
      filtro: '',
      loading: false,
      currentPage: 1,
      itemsPerPage: 10,
      selectedType: null,
      logTypes: [
        { label: 'Todos', value: null },
        { label: 'Información', value: 'INFO' },
        { label: 'Advertencia', value: 'WARNING' },
        { label: 'Error', value: 'ERROR' }
      ]
    };
  },
  computed: {
    filteredLogs() {
      return this.logs.filter(log => {
        const matchesSearch = (
          log.user_name.toLowerCase().includes(this.filtro.toLowerCase()) ||
          log.accion.toLowerCase().includes(this.filtro.toLowerCase()) ||
          log.detalles.toLowerCase().includes(this.filtro.toLowerCase())
        );
        // If selectedType is null or selectedType.value is null (Todos option), show all types
        // Otherwise, only show logs that match the selected type
        const matchesType = !this.selectedType || this.selectedType.value === null || log.type === this.selectedType.value;
        return matchesSearch && matchesType;
      });
    },
    totalPages() {
      return Math.ceil(this.filteredLogs.length / this.itemsPerPage);
    },
    paginatedLogs() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredLogs.slice(start, end);
    }
  },
  mounted() {
    this.loadLogs();
  },
  methods: {
    async loadLogs() {
      this.loading = true;
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('No authentication token found');
        }

        const response = await fetch('http://localhost:8000/api/logs', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error('Invalid response format - expected JSON');
        }

        const data = await response.json();
        this.logs = Array.isArray(data) ? data : [];
      } catch (error) {
        console.error('Error loading logs:', error);
        this.logs = [];
        this.$toast?.add({
          severity: 'error',
          summary: 'Error',
          detail: 'No se pudieron cargar los registros. Por favor, intente nuevamente.',
          life: 5000
        });
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      const options = { 
        year: 'numeric', 
        month: '2-digit', 
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      };
      return new Date(dateString).toLocaleString('es-ES', options);
    }
  }
};
</script>

<style scoped>
.type-filter {
  margin-left: 1rem;
  min-width: 200px;
}

.log-type-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.log-type-badge.info {
  background-color: #e3f2fd;
  color: #1976d2;
}

.log-type-badge.warning {
  background-color: #fff3e0;
  color: #f57c00;
}

.log-type-badge.error {
  background-color: #ffebee;
  color: #d32f2f;
}

.loading-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  padding: 2rem;
  font-size: 1.1rem;
  color: var(--text-color-secondary);
}

.loading-container i {
  font-size: 2rem;
}

/* Pagination Styles */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 0.75rem;
  padding: 0.3rem;
}

.page-numbers {
  display: flex;
  justify-content: center;
  margin: 0 0.3rem;
}

.pagination-button {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  color: #495057;
  padding: 0.25rem 0.5rem;
  margin: 0 0.15rem;
  border-radius: 3px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.85rem;
}

.pagination-button:hover {
  background-color: #e9ecef;
}

.pagination-button.active {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
  color: white;
}

.pagination-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>