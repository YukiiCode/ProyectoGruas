<template>
  <div class="container">
    <div class="page-header">
      <h2>Gestión de Retiradas</h2>
      <div class="header-actions">
        <router-link to="/nueva-retirada" class="btn btn-primary">
          <i class="pi pi-plus"></i>
          Nueva Retirada
        </router-link>
      </div>
      <div class="filter-container">
        <div class="search-box">
          <i class="pi pi-search"></i>
          <input v-model="filtro" type="text" placeholder="Buscar por NIF o nombre..." class="form-control" />
        </div>
      </div>
    </div>
    <div class="table-container" v-if="!loading">
      <table class="custom-table">
        <thead>
          <tr>
            <th>ID Vehículo</th>
            <th>Nombre</th>
            <th>NIF</th>
            <th>Población</th>
            <th>Fecha</th>
            <th>Agente</th>
            <th>Importe Retirada (€)</th>
            <th>Importe Depósito (€)</th>
            <th>Total (€)</th>
            <th>Forma de Pago</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="retirada in retiradasFiltradas" :key="retirada.id">
            <td>{{ retirada.vehiculo_id }}</td>
            <td>{{ retirada.nombre }}</td>
            <td><span class="nif-badge">{{ retirada.nif }}</span></td>
            <td>{{ retirada.poblacion }}</td>
            <td><span class="fecha">{{ formatFecha(retirada.fecha) }}</span></td>
            <td>{{ retirada.agente }}</td>
            <td>{{ formatTotal(retirada.importeretirada) }} €</td>
            <td>{{ formatTotal(retirada.importedeposito) }} €</td>
            <td><span class="total">{{ formatTotal(retirada.total) }} €</span></td>
            <td><span class="pago-badge">{{ retirada.opcionespago }}</span></td>
            <td>
              <button @click="generatePDF(retirada)" class="btn btn-primary">
                <i class="pi pi-file-pdf"></i>
                Generar Recibo
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="loading-container">
      <i class="pi pi-spin pi-spinner"></i>
      <span>Cargando retiradas...</span>
    </div>
  </div>
</template>

<script>
import { jsPDF } from "jspdf";

export default {
  name: 'Retiradas',
  data() {
    return {
      retiradas: [],
      filtro: '',
      loading: false
    }
  },
  computed: {
    retiradasFiltradas() {
      if (!Array.isArray(this.retiradas)) return [];
      return this.retiradas.filter(retirada =>
        retirada.nif.toLowerCase().includes(this.filtro.toLowerCase()) ||
        retirada.nombre.toLowerCase().includes(this.filtro.toLowerCase())
      );
    }
  },
  mounted() {
    this.cargarRetiradas();
  },
  methods: {
    async cargarRetiradas() {
      this.loading = true;
      try {
        const response = await fetch('/api/retiradas', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        const data = await response.json();
        this.retiradas = data;
      } catch (error) {
        console.error('Error al cargar retiradas:', error);
        this.retiradas = [];
      } finally {
        this.loading = false;
      }
    },
    formatFecha(fecha) {
      return new Date(fecha).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    formatTotal(total) {
      return Number(total).toLocaleString('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    },
    generatePDF(retirada) {
      // Create a new instance of jsPDF
      const doc = new jsPDF();

      // Configurar márgenes y estilos
      const marginX = 10;
      const marginY = 10;

      // Agregar encabezado
      this.addHeader(doc);

      // Agregar información de la empresa
      this.addCompanyInfo(doc, marginX, marginY);

      // Agregar información del cliente
      this.addClientInfo(doc, retirada, marginX, marginY);

      // Agregar tabla de servicios
      this.addServiceTable(doc, retirada, marginX, marginY);

      // Guardar el PDF
      doc.save(`Factura_Retirada_${retirada.id}.pdf`);
    },

    // Agregar encabezado
    addHeader(doc) {
      doc.setFontSize(18);
      doc.setTextColor("#000000");
      doc.text("Factura de Retirada", 105, 20, null, null, "center");
    },

    // Agregar información de la empresa
    addCompanyInfo(doc, marginX, marginY) {
      doc.setFontSize(12);
      doc.setTextColor("#333333");
      doc.text("Empresa de Grúas", marginX, marginY + 30);
      doc.text("Dirección: Calle Fernando Alonso", marginX, marginY + 36);
      doc.text("Teléfono: 666 23 22 21", marginX, marginY + 42);
      doc.text("Email: contacto@gruas.com", marginX, marginY + 48);
    },

    // Agregar información del cliente
    addClientInfo(doc, retirada, marginX, marginY) {
      doc.setFontSize(12);
      doc.setTextColor("#333333");
      doc.text(`Cliente: ${retirada.nombre}`, marginX, marginY + 60);
      doc.text(`NIF: ${retirada.nif}`, marginX, marginY + 66);
      doc.text(`Domicilio: ${retirada.domicilio}`, marginX, marginY + 72);
      doc.text(`Población: ${retirada.poblacion}`, marginX, marginY + 78);
      doc.text(`Provincia: ${retirada.provincia}`, marginX, marginY + 84);
      doc.text(`Fecha: ${this.formatFecha(retirada.fecha)}`, marginX, marginY + 90);
    },

    // Agregar tabla de servicios
    addServiceTable(doc, retirada, marginX, marginY) {
      doc.setFontSize(14);
      doc.setTextColor("#000000");
      doc.text("Descripción", marginX, marginY + 100);
      doc.text("Cantidad", marginX + 90, marginY + 100);
      doc.text("Precio", marginX + 130, marginY + 100);
      doc.text("Total", marginX + 170, marginY + 100);

      doc.setFontSize(12);
      doc.setTextColor("#333333");
      doc.text("Servicio de retirada", marginX, marginY + 110);
      doc.text("1", marginX + 90, marginY + 110, null, null, "right");
      doc.text(`${this.formatTotal(retirada.importeretirada)} €`, marginX + 130, marginY + 110, null, null, "right");
      doc.text(`${this.formatTotal(retirada.total)} €`, marginX + 170, marginY + 110, null, null, "right");

      // Total final
      doc.setFontSize(14);
      doc.setTextColor("#000000");
      doc.text(`Total: ${this.formatTotal(retirada.total)} €`, marginX + 170, marginY + 130, null, null, "right");
    },

    // Formatear fecha
    formatFecha(fecha) {
      const date = new Date(fecha);
      return `${date.getDate().toString().padStart(2, "0")}/${(date.getMonth() + 1).toString().padStart(2, "0")}/${date.getFullYear()}`;
    },

    // Formatear números (decimales)
    formatTotal(valor) {
      return parseFloat(valor).toFixed(2).replace(".", ",");

    },
  }
}
</script>

<style scoped>
.page-header {
  margin-bottom: 2rem;
}

.page-header h2 {
  color: var(--primary-color);
  margin-bottom: 1rem;
}

.search-box {
  position: relative;
  max-width: 300px;
}

.search-box i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-color);
  opacity: 0.5;
}

.search-box input {
  padding-left: 35px;
}

.table-container {
  background: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
  overflow: hidden;
}

.nif-badge {
  background-color: var(--primary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-family: monospace;
  font-size: 0.9em;
}

.fecha {
  color: var(--text-color);
  opacity: 0.8;
}

.total {
  font-weight: 600;
  color: var(--primary-color);
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.loading-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 2rem;
  background: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
}

.loading-container i {
  font-size: 1.5rem;
  color: var(--secondary-color);
}
</style>