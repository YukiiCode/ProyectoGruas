<template>
  <div class="container">
    <div class="page-header">
      <h2>Gestión de Retiradas</h2>
      <div class="header-actions">
        <button @click="showRetiradaForm = true" class="btn btn-primary">
          <i class="pi pi-plus"></i>
          Procesar Retirada
        </button>
      </div>
      <div class="filter-container">
        <div class="search-box">
          <i class="pi pi-search"></i>
          <input v-model="filtro" type="text" placeholder="Buscar por NIF o nombre..." class="form-control" />
          <button v-if="filtro" @click="filtro = ''" class="clear-search">
            <i class="pi pi-times"></i>
          </button>
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
      
      <div v-if="retiradasFiltradas.length === 0" class="no-results">
        <i class="pi pi-exclamation-circle"></i>
        <p>No se encontraron retiradas{{ filtro ? ' con el criterio "' + filtro + '"' : '' }}</p>
      </div>
    </div>
    <div v-else class="loading-container">
      <i class="pi pi-spin pi-spinner"></i>
      <span>Cargando retiradas...</span>
    </div>
    
    <!-- Componente de formulario de retirada -->
    <retirada-form v-model:visible="showRetiradaForm" :dialog-header="'Procesar Retirada de Vehículo'" 
      @save="onRetiradaSaved" @close="showRetiradaForm = false" />
  </div>
</template>

<script>
import { jsPDF } from "jspdf";
import RetiradaForm from './RetiradaForm.vue';

export default {
  name: 'Retiradas',
  components: {
    RetiradaForm
  },
  data() {
    return {
      retiradas: [],
      filtro: '',
      loading: false,
      showRetiradaForm: false
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
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          if (response.status === 401) {
            localStorage.removeItem('token');
            this.$parent.isAuthenticated = false;
            this.$router.push('/login');
            return;
          }
          throw new Error('Error al cargar retiradas');
        }
        
        this.retiradas = await response.json();
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
      if (total === null || total === undefined) return '0.00';
      const num = parseFloat(total);
      return isNaN(num) ? '0.00' : num.toLocaleString('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    },
    generatePDF(retirada) {
      // Create a new instance of jsPDF
      const doc = new jsPDF();

      // Configurar márgenes y estilos
      const marginX = 20;
      const marginY = 20;
      const pageWidth = doc.internal.pageSize.width;
      
      // Agregar logo (simulado con un rectángulo azul)
      doc.setFillColor(0, 0, 255);
      doc.rect(marginX, marginY, 30, 20, 'F');
      
      // Agregar número de expediente
      doc.setFillColor(211, 211, 211);
      doc.rect(pageWidth - 100, marginY, 80, 20, 'F');
      doc.setFontSize(10);
      doc.setTextColor(0, 0, 0);
      doc.text("Número Expediente", pageWidth - 60, marginY + 8, null, null, "center");
      doc.setFontSize(12);
      doc.text(`${new Date().getFullYear()}${String(retirada.id).padStart(6, '0')}`, pageWidth - 60, marginY + 16, null, null, "center");
      
      // Agregar texto legal
      doc.setFontSize(9);
      doc.setTextColor(0, 0, 0);
      const textoLegal = "La Policía Local ha procedido a retirar el vehículo que más abajo se reseña, en cumplimiento de la Ordenanza Municipal Reguladora 2-07 (BOP núm. 247 de 29/12/2009) sobre Retirada y Depósito de vehículos de la vía pública.";
      const splitTextoLegal = doc.splitTextToSize(textoLegal, pageWidth - 2 * marginX);
      doc.text(splitTextoLegal, marginX, marginY + 30);
      
      // DATOS DEL VEHÍCULO
      const yVehiculo = marginY + 45;
      this.addSectionHeader(doc, "DATOS DEL VEHÍCULO", marginX, yVehiculo, pageWidth - 2 * marginX);
      
      // Tabla de datos del vehículo
      const yVehiculoData = yVehiculo + 10;
      doc.setDrawColor(0);
      doc.setLineWidth(0.1);
      
      // Primera fila
      doc.line(marginX, yVehiculoData, pageWidth - marginX, yVehiculoData); // Línea superior
      doc.line(marginX, yVehiculoData, marginX, yVehiculoData + 15); // Línea izquierda
      doc.line((pageWidth - marginX) / 2, yVehiculoData, (pageWidth - marginX) / 2, yVehiculoData + 15); // Línea media
      doc.line(pageWidth - marginX, yVehiculoData, pageWidth - marginX, yVehiculoData + 15); // Línea derecha
      doc.line(marginX, yVehiculoData + 15, pageWidth - marginX, yVehiculoData + 15); // Línea inferior
      
      // Etiquetas y valores primera fila
      doc.setFontSize(10);
      doc.text("Matrícula", marginX + 5, yVehiculoData + 5);
      doc.text("Marca", (pageWidth - marginX) / 2 + 5, yVehiculoData + 5);
      doc.text("Modelo", (pageWidth - marginX) / 2 + (pageWidth - marginX) / 4 + 5, yVehiculoData + 5);
      
      doc.setFontSize(11);
      doc.text(retirada.vehiculo?.matricula || "", marginX + 5, yVehiculoData + 12);
      doc.text(retirada.vehiculo?.marca || "", (pageWidth - marginX) / 2 + 5, yVehiculoData + 12);
      doc.text(retirada.vehiculo?.modelo || "", (pageWidth - marginX) / 2 + (pageWidth - marginX) / 4 + 5, yVehiculoData + 12);
      
      // Segunda fila
      const yVehiculoData2 = yVehiculoData + 15;
      doc.line(marginX, yVehiculoData2, marginX, yVehiculoData2 + 15); // Línea izquierda
      doc.line((pageWidth - marginX) / 2, yVehiculoData2, (pageWidth - marginX) / 2, yVehiculoData2 + 15); // Línea media
      doc.line(pageWidth - marginX, yVehiculoData2, pageWidth - marginX, yVehiculoData2 + 15); // Línea derecha
      doc.line(marginX, yVehiculoData2 + 15, pageWidth - marginX, yVehiculoData2 + 15); // Línea inferior
      
      // Etiquetas y valores segunda fila
      doc.setFontSize(10);
      doc.text("Lugar de recogida", marginX + 5, yVehiculoData2 + 5);
      doc.text("Fecha y hora de recogida", (pageWidth - marginX) / 2 + 5, yVehiculoData2 + 5);
      
      doc.setFontSize(11);
      doc.text(retirada.vehiculo?.lugar || "", marginX + 5, yVehiculoData2 + 12);
      const fecha = new Date(retirada.fecha);
      const horaFormateada = `${String(fecha.getHours()).padStart(2, "0")}:${String(fecha.getMinutes()).padStart(2, "0")}`;
      doc.text(`${this.formatFecha(retirada.fecha)} ${horaFormateada}`, (pageWidth - marginX) / 2 + 5, yVehiculoData2 + 12);
      
      // DATOS DEL PROPIETARIO
      const yPropietario = yVehiculoData2 + 25;
      this.addSectionHeader(doc, "DATOS DEL PROPIETARIO", marginX, yPropietario, pageWidth - 2 * marginX);
      
      // Tabla de datos del propietario
      const yPropietarioData = yPropietario + 10;
      
      // Primera fila propietario
      doc.line(marginX, yPropietarioData, pageWidth - marginX, yPropietarioData); // Línea superior
      doc.line(marginX, yPropietarioData, marginX, yPropietarioData + 15); // Línea izquierda
      doc.line((pageWidth - marginX) / 2, yPropietarioData, (pageWidth - marginX) / 2, yPropietarioData + 15); // Línea media
      doc.line(pageWidth - marginX, yPropietarioData, pageWidth - marginX, yPropietarioData + 15); // Línea derecha
      doc.line(marginX, yPropietarioData + 15, pageWidth - marginX, yPropietarioData + 15); // Línea inferior
      
      // Etiquetas y valores primera fila propietario
      doc.setFontSize(10);
      doc.text("Nombre y apellidos", marginX + 5, yPropietarioData + 5);
      doc.text("NIF", (pageWidth - marginX) / 2 + 5, yPropietarioData + 5);
      
      doc.setFontSize(11);
      doc.text(retirada.nombre, marginX + 5, yPropietarioData + 12);
      doc.text(retirada.nif, (pageWidth - marginX) / 2 + 5, yPropietarioData + 12);
      
      // Segunda fila propietario
      const yPropietarioData2 = yPropietarioData + 15;
      doc.line(marginX, yPropietarioData2, marginX, yPropietarioData2 + 15); // Línea izquierda
      doc.line(pageWidth - marginX, yPropietarioData2, pageWidth - marginX, yPropietarioData2 + 15); // Línea derecha
      doc.line(marginX, yPropietarioData2 + 15, pageWidth - marginX, yPropietarioData2 + 15); // Línea inferior
      
      // Etiquetas y valores segunda fila propietario
      doc.setFontSize(10);
      doc.text("Domicilio", marginX + 5, yPropietarioData2 + 5);
      
      doc.setFontSize(11);
      doc.text(retirada.domicilio, marginX + 5, yPropietarioData2 + 12);
      
      // Tercera fila propietario
      const yPropietarioData3 = yPropietarioData2 + 15;
      doc.line(marginX, yPropietarioData3, marginX, yPropietarioData3 + 15); // Línea izquierda
      doc.line((pageWidth - marginX) / 3, yPropietarioData3, (pageWidth - marginX) / 3, yPropietarioData3 + 15); // Línea divisoria 1
      doc.line(2 * (pageWidth - marginX) / 3, yPropietarioData3, 2 * (pageWidth - marginX) / 3, yPropietarioData3 + 15); // Línea divisoria 2
      doc.line(pageWidth - marginX, yPropietarioData3, pageWidth - marginX, yPropietarioData3 + 15); // Línea derecha
      doc.line(marginX, yPropietarioData3 + 15, pageWidth - marginX, yPropietarioData3 + 15); // Línea inferior
      
      // Etiquetas y valores tercera fila propietario
      doc.setFontSize(10);
      doc.text("Población", marginX + 5, yPropietarioData3 + 5);
      doc.text("Provincia", (pageWidth - marginX) / 3 + 5, yPropietarioData3 + 5);
      doc.text("Permiso", 2 * (pageWidth - marginX) / 3 + 5, yPropietarioData3 + 5);
      
      doc.setFontSize(11);
      doc.text(retirada.poblacion, marginX + 5, yPropietarioData3 + 12);
      doc.text(retirada.provincia, (pageWidth - marginX) / 3 + 5, yPropietarioData3 + 12);
      doc.text(retirada.permiso || "0", 2 * (pageWidth - marginX) / 3 + 5, yPropietarioData3 + 12);
      
      // DATOS DE ENTREGA
      const yEntrega = yPropietarioData3 + 25;
      this.addSectionHeader(doc, "DATOS DE ENTREGA", marginX, yEntrega, pageWidth - 2 * marginX);
      
      // Tabla de datos de entrega
      const yEntregaData = yEntrega + 10;
      
      // Primera fila entrega
      doc.line(marginX, yEntregaData, pageWidth - marginX, yEntregaData); // Línea superior
      doc.line(marginX, yEntregaData, marginX, yEntregaData + 15); // Línea izquierda
      doc.line((pageWidth - marginX) / 3, yEntregaData, (pageWidth - marginX) / 3, yEntregaData + 15); // Línea divisoria 1
      doc.line(2 * (pageWidth - marginX) / 3, yEntregaData, 2 * (pageWidth - marginX) / 3, yEntregaData + 15); // Línea divisoria 2
      doc.line(pageWidth - marginX, yEntregaData, pageWidth - marginX, yEntregaData + 15); // Línea derecha
      doc.line(marginX, yEntregaData + 15, pageWidth - marginX, yEntregaData + 15); // Línea inferior
      
      // Etiquetas y valores primera fila entrega
      doc.setFontSize(10);
      doc.text("Fecha y hora", marginX + 5, yEntregaData + 5);
      doc.text("Agente", (pageWidth - marginX) / 3 + 5, yEntregaData + 5);
      doc.text("Forma de pago", 2 * (pageWidth - marginX) / 3 + 5, yEntregaData + 5);
      
      doc.setFontSize(11);
      const fechaHoraEntrega = new Date(retirada.fecha);
      fechaHoraEntrega.setHours(fechaHoraEntrega.getHours() + 1); // Simulando una hora después de la retirada
      doc.text(`${this.formatFecha(fechaHoraEntrega)} ${fechaHoraEntrega.getHours()}:${fechaHoraEntrega.getMinutes()}`, marginX + 5, yEntregaData + 12);
      doc.text(retirada.agente, (pageWidth - marginX) / 3 + 5, yEntregaData + 12);
      doc.text(retirada.opcionespago || "Contado", 2 * (pageWidth - marginX) / 3 + 5, yEntregaData + 12);
      
      // Segunda fila entrega (importes)
      const yEntregaData2 = yEntregaData + 15;
      doc.line(marginX, yEntregaData2, marginX, yEntregaData2 + 15); // Línea izquierda
      doc.line((pageWidth - marginX) / 3, yEntregaData2, (pageWidth - marginX) / 3, yEntregaData2 + 15); // Línea divisoria 1
      doc.line(2 * (pageWidth - marginX) / 3, yEntregaData2, 2 * (pageWidth - marginX) / 3, yEntregaData2 + 15); // Línea divisoria 2
      doc.line(pageWidth - marginX, yEntregaData2, pageWidth - marginX, yEntregaData2 + 15); // Línea derecha
      doc.line(marginX, yEntregaData2 + 15, pageWidth - marginX, yEntregaData2 + 15); // Línea inferior
      
      // Etiquetas y valores segunda fila entrega
      doc.setFontSize(10);
      doc.text("Importe Retirada", marginX + 5, yEntregaData2 + 5);
      doc.text("Importe Depósito", (pageWidth - marginX) / 3 + 5, yEntregaData2 + 5);
      doc.text("Total a pagar", 2 * (pageWidth - marginX) / 3 + 5, yEntregaData2 + 5);
      
      doc.setFontSize(11);
      doc.text(`${this.formatTotal(retirada.importeretirada)} €`, marginX + 5, yEntregaData2 + 12);
      doc.text(`${this.formatTotal(retirada.importedeposito)} €`, (pageWidth - marginX) / 3 + 5, yEntregaData2 + 12);
      doc.text(`${this.formatTotal(retirada.total)} €`, 2 * (pageWidth - marginX) / 3 + 5, yEntregaData2 + 12);
      
      // Pie de página
      const yFooter = yEntregaData2 + 25;
      doc.setFontSize(9);
      doc.text("Recibe copia y firma", marginX, yFooter);
      doc.text("Conforme el conductor", marginX, yFooter + 5);
      
      const fechaActual = new Date();
      const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
      doc.text(`En Huelva a ${fechaActual.getDate()} de ${meses[fechaActual.getMonth()]} de ${fechaActual.getFullYear()}`, pageWidth - marginX - 80, yFooter + 5);
      
      // Guardar el PDF
      doc.save(`Recibo_Retirada_${retirada.id}.pdf`);
    },
    
    // Método para agregar encabezados de sección
    addSectionHeader(doc, title, x, y, width) {
      doc.setFillColor(211, 211, 211);
      doc.rect(x, y, width, 8, 'F');
      doc.setFontSize(11);
      doc.setTextColor(0, 0, 0);
      doc.text(title, x + width / 2, y + 5.5, null, null, "center");
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
    
    onRetiradaSaved() {
      this.showRetiradaForm = false;
      this.cargarRetiradas();
    },
  }
}
</script>

<style scoped>
.page-header {
  margin-bottom: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.page-header h2 {
  color: var(--primary-color);
  margin-bottom: 0.5rem;
}

.header-actions {
  display: flex;
  justify-content: flex-end;
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
  width: 100%;
  border-radius: 6px;
  border: 1px solid #ddd;
  padding: 0.6rem 2.2rem 0.6rem 2.2rem;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  background-color: white;
}

.clear-search {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6c757d;
  cursor: pointer;
  padding: 3px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
}

.table-container {
  background: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
  overflow: hidden;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
}

.custom-table th,
.custom-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.custom-table th {
  background-color: #f8f9fa;
  font-weight: 600;
  color: #495057;
}

.nif-badge {
  background-color: var(--primary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-family: monospace;
  font-size: 0.9em;
}

.pago-badge {
  background-color: var(--secondary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
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
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
  border: none;
}

.btn-primary {
  background-color: var(--secondary-color);
  color: white;
}

.btn-primary:hover {
  background-color: var(--primary-color);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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

.no-results {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  color: #6c757d;
  text-align: center;
}

.no-results i {
  font-size: 2.5rem;
  margin-bottom: 0.8rem;
  color: #dee2e6;
}

.no-results p {
  font-size: 1rem;
  margin: 0;
}
</style>