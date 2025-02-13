<template>
  <div class="container">
    <!-- Card principal -->
    <!-- Campo de Filtro -->
    <div class="filter-container p-inputgroup">
      <span class="p-inputgroup-addon">
        <i class="pi pi-search"></i>
      </span>
      <InputText v-model="filtro" placeholder="Filtrar por matrícula..." class="form-control" />
    </div>

    <!-- DataTable de Vehículos -->
    <DataTable :value="vehiculosFiltrados" :paginator="true" :rows="10"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink " 
      responsiveLayout="scroll" class="custom-table mt-4">
      <template #header>
        <h3>Vehículos Registrados</h3>
      </template>
      <template #empty>
        <div class="no-results">No se encontraron resultados</div>
      </template>
      <template #loading>
        <div class="loading-message">Cargando datos...</div>
      </template>
      <Column field="matricula" header="Matrícula" sortable></Column>
      <Column field="marca" header="Marca" sortable></Column>
      <Column field="modelo" header="Modelo" sortable></Column>
      <Column field="color" header="Color"></Column>
      <Column field="tipovehiculo" header="Tipo de Vehículo"></Column>
      <Column field="estado" header="Estado"></Column>
    </DataTable>
  </div>
</template>

<script>
import apiClient from '@/services/api';
import { ref, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';

export default {
  components: {
    DataTable,
    Column,
    InputText,
  },
  setup() {
    const vehiculos = ref([]);
    const filtro = ref('');
    const loading = ref(false);

    const cargarVehiculos = async () => {
      loading.value = true;
      try {
        const response = await apiClient.get('/vehiculos');
        vehiculos.value = response.data;
      } catch (error) {
        console.error('Error al cargar vehículos:', error);
        vehiculos.value = [];
      } finally {
        loading.value = false;
      }
    };

    const vehiculosFiltrados = computed(() => {
      if (!Array.isArray(vehiculos.value)) return [];
      return vehiculos.value.filter((vehiculo) =>
        vehiculo.matricula.toLowerCase().includes(filtro.value.toLowerCase())
      );
    });

    cargarVehiculos();

    return {
      vehiculos,
      filtro,
      vehiculosFiltrados,
      loading,
    };
  },
};
</script>

<style scoped>
.container {
  padding: 20px;
}

.filter-container {
  margin-bottom: 20px;
}

.no-results,
.loading-message {
  text-align: center;
  color: #888;
  font-style: italic;
}

.custom-table {
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>