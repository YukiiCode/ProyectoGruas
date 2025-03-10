<template>
  <div class="table-container">
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
        <tr v-for="vehiculo in paginatedVehiculos" :key="vehiculo.id">
          <td><span class="matricula-badge">{{ vehiculo.matricula }}</span></td>
          <td>{{ vehiculo.marca }}</td>
          <td>{{ vehiculo.modelo }}</td>
          <td>
            <span class="color-dot" :style="{ backgroundColor: getColorHexValue(vehiculo.color) }"></span>
            {{ vehiculo.color }}
          </td>
          <td><span class="tipo-badge">{{ getTipoVehiculoDisplay(vehiculo.tipovehiculo) }}</span></td>
          <td>
            <span :class="['status-badge', vehiculo.estado === 'Activo' ? 'status-active' : 'status-inactive']">
              {{ vehiculo.estado }}
            </span>
          </td>
          <td class="actions">
            <button class="btn-action edit" @click="$emit('edit', vehiculo)">
              <i class="pi pi-pencil"></i>
            </button>
            <button class="btn-action delete" @click="$emit('delete', vehiculo)">
              <i class="pi pi-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination" v-if="totalPages >= 1">
      <button 
        class="pagination-button" 
        :disabled="currentPage === 1" 
        @click="changePage(currentPage - 1)"
      >
        <i class="pi pi-chevron-left"></i>
      </button>
      <div class="page-numbers">
        <button 
          v-for="page in totalPages" 
          :key="page" 
          class="pagination-button" 
          :class="{ active: currentPage === page }" 
          @click="changePage(page)"
        >
          {{ page }}
        </button>
      </div>
      <button 
        class="pagination-button" 
        :disabled="currentPage === totalPages" 
        @click="changePage(currentPage + 1)"
      >
        <i class="pi pi-chevron-right"></i>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VehiculosTable',
  props: {
    vehiculos: {
      type: Array,
      required: true
    }
  },
  emits: ['edit', 'delete'],
  data() {
    return {
      currentPage: 1,
      itemsPerPage: 10,
      tiposVehiculo: {
        'Motocicleta_aperos_motocarros_y_similares': 'Motocicleta, aperos, motocarros y similares',
        'Turismo_hasta_12cv': 'Turismo hasta 12 cv ó Remolques hasta 750 kg',
        'Turismo_mas_12cv': 'Turismos más de 12 cv ó Remolques más de 750 kg',
        'Vehiculos_especiales': 'Vehículos especiales',
        'Vehiculos_de_cortesia': 'Vehículos de cortesía',
        'Chatarra': 'Chatarra'
      },
      coloresMap: {
        'Blanco': '#FFFFFF',
        'Negro': '#000000',
        'Gris': '#808080',
        'Plata': '#C0C0C0',
        'Rojo': '#FF0000',
        'Azul': '#0000FF',
        'Verde': '#008000',
        'Amarillo': '#FFFF00',
        'Naranja': '#FFA500',
        'Marrón': '#A52A2A',
        'Beige': '#F5F5DC',
        'Granate': '#800000',
        'Azul marino': '#000080',
        'Verde oscuro': '#006400'
      }
    }
  },
  methods: {
    getTipoVehiculoDisplay(tipo) {
      return this.tiposVehiculo[tipo] || tipo;
    },
    getColorHexValue(colorName) {
      return this.coloresMap[colorName] || '#FFFFFF'; // Default to white if color not found
    }
  },
  computed: {
    totalPages() {
      return Math.ceil(this.vehiculos.length / this.itemsPerPage);
    },
    paginatedVehiculos() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.vehiculos.slice(start, end);
    }
  },
  methods: {
    getTipoVehiculoDisplay(tipo) {
      return this.tiposVehiculo[tipo] || tipo;
    },
    getColorHexValue(colorName) {
      return this.coloresMap[colorName] || '#FFFFFF';
    },
    changePage(page) {
      this.currentPage = page;
    }
  }
};
</script>

<style scoped>
.table-container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
}

.custom-table th,
.custom-table td {
  padding: 0.75rem 1rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.custom-table th {
  background-color: #f8f9fa;
  font-weight: 600;
  color: #495057;
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
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.tipo-badge {
  display: inline-block;
  background-color: #3498db;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.75em;
  max-width: 200px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  text-align: center;
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
