<template>
  <div class="container">
    <!-- Campo de Filtro -->
    <div class="filter-container p-inputgroup">
      <span class="p-inputgroup-addon">
        <i class="pi pi-search"></i>
      </span>
      <InputText v-model="filtro" placeholder="Filtrar por matrícula..." class="form-control" />
    </div>

    <!-- DataTable de Retiradas -->
    <DataTable :value="retiradasFiltradas" :paginator="true" :rows="10"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
      responsiveLayout="scroll" class="custom-table mt-4">
      <template #header>
        <h3>Retiradas Registradas</h3>
      </template>
      <template #empty>
        <div class="no-results">No se encontraron resultados</div>
      </template>
      <template #loading>
        <div class="loading-message">Cargando datos...</div>
      </template>
      <Column field="nombre" header="Nombre" sortable></Column>
      <Column field="nif" header="NIF" sortable></Column>
      <Column field="fecha" header="Fecha" sortable></Column>
      <Column field="total" header="Total (€)" sortable></Column>
      <Column header="Acciones">
        <template #body="slotProps">
          <button class="btn-primary" @click="generarRecibo(slotProps.data)">
            Generar Recibo
          </button>
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script>
import apiClient from '@/services/api';
import { ref, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { generarRecibo } from '@/utils/pdfutils';

export default {
  components: {
    DataTable,
    Column,
    Card,
    InputText,
    Button,
  },
  setup() {
    const retiradas = ref([]);
    const filtro = ref('');
    const loading = ref(false);

    const cargarRetiradas = async () => {
      loading.value = true;
      try {
        const response = await apiClient.get('/retiradas');
        retiradas.value = response.data;
      } catch (error) {
        console.error('Error al cargar retiradas:', error);
        retiradas.value = [];
      } finally {
        loading.value = false;
      }
    };

    const retiradasFiltradas = computed(() => {
      if (!Array.isArray(retiradas.value)) return [];
      return retiradas.value.filter(
        (retirada) =>
          retirada.nombre.toLowerCase().includes(filtro.value.toLowerCase()) ||
          retirada.nif.toLowerCase().includes(filtro.value.toLowerCase())
      );
    });

    cargarRetiradas();

    const generarReciboLocal = (retirada) => {
      const datos = {
        nombre: retirada.nombre,
        nif: retirada.nif,
        fecha: retirada.fecha,
        total: retirada.total,
        items: [
          { id: 1, descripcion: "Servicio de grúa", cantidad: 1, precio: 150 },
          { id: 2, descripcion: "Reparación de vehículo", cantidad: 1, precio: 100.5 },
        ],
      };
      generarReciboPDF(datos); // Usa la función renombrada
    };

    return {
      retiradas,
      filtro,
      retiradasFiltradas,
      generarRecibo,
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