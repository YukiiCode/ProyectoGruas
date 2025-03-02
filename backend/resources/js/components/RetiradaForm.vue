<template>
  <Dialog :visible="visible" :header="dialogHeader" modal class="p-fluid retirada-dialog"
    :style="{ width: '80vw', maxWidth: '900px' }" @update:visible="$emit('update:visible', $event)">
    <div class="form-grid">
      <!-- Sección de búsqueda de vehículo -->
      <div class="section-header">
        <h3>Datos del Vehículo</h3>
      </div>

      <div class="form-group">
        <label for="vehiculo">Seleccionar Vehículo</label>
        <Dropdown id="vehiculo" v-model="selectedVehiculo" :options="vehiculosEnDeposito" optionLabel="matricula"
          placeholder="Seleccione un vehículo" class="form-control" required @change="onVehiculoChange">
          <template #option="slotProps">
            <div class="vehiculo-option">
              <span class="matricula">{{ slotProps.option.matricula }}</span>
              <span class="details">{{ slotProps.option.marca }} {{ slotProps.option.modelo }} - {{
    slotProps.option.color }}</span>
            </div>
          </template>
        </Dropdown>
        <small v-if="errors.vehiculo" class="p-error">{{ errors.vehiculo }}</small>
        <small v-else-if="!selectedVehiculo && formSubmitted" class="p-error">Debe seleccionar un vehículo.</small>
      </div>

      <div class="form-group">
        <label>Detalles del Vehículo</label>
        <div v-if="selectedVehiculo" class="vehiculo-details">
          <div class="detail-item">
            <span class="label">Marca:</span>
            <span class="value">{{ selectedVehiculo.marca }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Modelo:</span>
            <span class="value">{{ selectedVehiculo.modelo }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Color:</span>
            <span class="value">{{ selectedVehiculo.color }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Tipo:</span>
            <span class="value">{{ getTipoVehiculoDisplay(selectedVehiculo.tipovehiculo) }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Fecha de entrada:</span>
            <span class="value">{{ formatFecha(selectedVehiculo.fechaentrada) }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Días en depósito:</span>
            <span class="value">{{ formData.fecha ? calcularDiasEnDeposito(selectedVehiculo.fechaentrada) : 'Pendiente de fecha de salida' }}</span>
          </div>
        </div>
        <div v-else class="no-vehiculo-selected">
          Seleccione un vehículo para ver sus detalles
        </div>
      </div>

      <!-- Sección de datos del propietario -->
      <div class="section-header">
        <h3>Datos del Propietario</h3>
      </div>

      <div class="form-group">
        <label for="nombre">Nombre</label>
        <InputText id="nombre" v-model="formData.nombre" type="text" class="form-control" required />
        <small v-if="errors.nombre" class="p-error">{{ errors.nombre }}</small>
        <small v-else-if="!formData.nombre && formSubmitted" class="p-error">El nombre es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="nif">NIF/NIE</label>
        <InputText id="nif" v-model="formData.nif" type="text" class="form-control" required />
        <small v-if="errors.nif" class="p-error">{{ errors.nif }}</small>
        <small v-else-if="!formData.nif && formSubmitted" class="p-error">El NIF/NIE es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="domicilio">Domicilio</label>
        <InputText id="domicilio" v-model="formData.domicilio" type="text" class="form-control" required />
        <small v-if="errors.domicilio" class="p-error">{{ errors.domicilio }}</small>
        <small v-else-if="!formData.domicilio && formSubmitted" class="p-error">El domicilio es obligatorio.</small>
      </div>

      <div class="form-group">
        <label for="poblacion">Población</label>
        <InputText id="poblacion" v-model="formData.poblacion" type="text" class="form-control" required />
        <small v-if="errors.poblacion" class="p-error">{{ errors.poblacion }}</small>
        <small v-else-if="!formData.poblacion && formSubmitted" class="p-error">La población es obligatoria.</small>
      </div>

      <div class="form-group">
        <label for="provincia">Provincia</label>
        <InputText id="provincia" v-model="formData.provincia" type="text" class="form-control" required />
        <small v-if="errors.provincia" class="p-error">{{ errors.provincia }}</small>
        <small v-else-if="!formData.provincia && formSubmitted" class="p-error">La provincia es obligatoria.</small>
      </div>

      <div class="form-group">
        <label for="permiso">Permiso de Conducir</label>
        <Dropdown id="permiso" v-model="formData.permiso" :options="tiposPermiso" 
          placeholder="Seleccione un tipo de permiso" class="form-control" required />
        <small v-if="errors.permiso" class="p-error">{{ errors.permiso }}</small>
        <small v-else-if="!formData.permiso && formSubmitted" class="p-error">El permiso es obligatorio.</small>
      </div>

      <!-- Sección de datos de la retirada -->
      <div class="section-header">
        <h3>Datos de la Retirada</h3>
      </div>

      <div class="form-group">
        <label for="fecha">Fecha de Retirada</label>
        <Calendar id="fecha" v-model="formData.fecha" :showTime="true" dateFormat="dd/mm/yy" class="form-control"
          required />
        <small v-if="errors.fecha" class="p-error">{{ errors.fecha }}</small>
        <small v-else-if="!formData.fecha && formSubmitted" class="p-error">La fecha es obligatoria.</small>
      </div>

      <div class="form-group">
        <label for="agente">Agente</label>
        <InputText id="agente" v-model="formData.agente" type="text" class="form-control" readonly required />
        <small v-if="errors.agente" class="p-error">{{ errors.agente }}</small>
        <small v-else-if="!formData.agente && formSubmitted" class="p-error">El agente es obligatorio.</small>
      </div>

      <!-- Sección de importes -->
      <div class="section-header">
        <h3>Importes</h3>
      </div>

      <div class="form-group">
        <label for="importeretirada">Importe Retirada (€)</label>
        <InputNumber id="importeretirada" v-model="formData.importeretirada" :readonly="true" mode="currency"
          currency="EUR" locale="es-ES" class="form-control" />
      </div>

      <div class="form-group">
        <label for="importedeposito">Importe Depósito (€)</label>
        <InputNumber id="importedeposito" v-model="formData.importedeposito" :readonly="true" mode="currency"
          currency="EUR" locale="es-ES" class="form-control" />
      </div>

      <div class="form-group">
        <label for="total">Total (€)</label>
        <InputNumber id="total" v-model="formData.total" :readonly="true" mode="currency" currency="EUR" locale="es-ES"
          class="form-control" />
      </div>

      <div class="form-group">
        <label for="opcionespago">Forma de Pago</label>
        <Dropdown id="opcionespago" v-model="formData.opcionespago" :options="opcionesPago"
          placeholder="Selecciona una forma de pago" class="form-control" required />
        <small v-if="errors.opcionespago" class="p-error">{{ errors.opcionespago }}</small>
        <small v-else-if="!formData.opcionespago && formSubmitted" class="p-error">La forma de pago es obligatoria.</small>
      </div>
    </div>

    <template #footer>
      <div class="dialog-footer">
        <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="onClose" />
        <Button label="Procesar Retirada" icon="pi pi-check" class="p-button-primary" @click="onSave" />
      </div>
    </template>
  </Dialog>

  <!-- Summary Modal -->
  <Dialog v-model:visible="showSummary" header="Resumen de Retirada" :modal="true">
    <div v-if="summaryData">
      <h3>Detalles de la Retirada</h3>
      <p><strong>Nombre:</strong> {{ summaryData.nombre }}</p>
      <p><strong>NIF:</strong> {{ summaryData.nif }}</p>
      <p><strong>Fecha:</strong> {{ formatFecha(summaryData.fecha) }}</p>
      <p><strong>Total:</strong> {{ formatTotal(summaryData.total) }} €</p>
      <!-- Add more details as needed -->
      <Button label="Descargar Factura" icon="pi pi-download" @click="downloadPDF" />
    </div>
  </Dialog>
</template>

<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import { jsPDF } from "jspdf";

export default {
  name: 'RetiradaForm',
  components: {
    Dialog,
    Button,
    InputText,
    InputNumber,
    Dropdown,
    Calendar
  },
  props: {
    visible: {
      type: Boolean,
      required: true
    },
    dialogHeader: {
      type: String,
      default: 'Procesar Retirada de Vehículo'
    }
  },
  emits: ['update:visible', 'save', 'close'],
  data() {
    return {
      formData: {
        nombre: '',
        nif: '',
        domicilio: '',
        poblacion: '',
        provincia: '',
        permiso: '',
        fecha: new Date(),
        agente: '',
        importeretirada: 0,
        importedeposito: 0,
        total: 0,
        opcionespago: null,

        vehiculo_id: null
      },
      selectedVehiculo: null,
      vehiculosEnDeposito: [],
      formSubmitted: false,
      errors: {
        vehiculo: '',
        nombre: '',
        nif: '',
        domicilio: '',
        poblacion: '',
        provincia: '',
        permiso: '',
        fecha: '',
        agente: '',
        opcionespago: ''
      },

      opcionesPago: ['Metálico', 'Tarjeta', 'Bizum'],
      tiposPermiso: ['AM', 'A1', 'A2', 'A', 'B', 'B+E', 'C1', 'C1+E', 'C', 'C+E', 'D1', 'D1+E', 'D', 'D+E', 'LCM', 'LVA'],
      tarifas: {
        'Motocicleta_aperos_motocarros_y_similares': { costoBase: 25, horasGratis: 0, costoPorHora: 5 },
        'Turismo_hasta_12cv': { costoBase: 100, horasGratis: 24, costoPorHora: 10 },
        'Turismo_mas_12cv': { costoBase: 130, horasGratis: 24, costoPorHora: 15 },
        'Vehiculos_especiales': { costoBase: 150, horasGratis: 0, costoPorHora: 20 },
        'Vehiculos_de_cortesia': { costoBase: 0, horasGratis: 0, costoPorHora: 0 },
        'Chatarra': { costoBase: 0, horasGratis: 0, costoPorHora: 0 }
      },
      tiposVehiculo: {
        'Motocicleta_aperos_motocarros_y_similares': 'Motocicleta, aperos, motocarros y similares',
        'Turismo_hasta_12cv': 'Turismo hasta 12 cv ó Remolques hasta 750 kg',
        'Turismo_mas_12cv': 'Turismos más de 12 cv ó Remolques más de 750 kg',
        'Vehiculos_especiales': 'Vehículos especiales',
        'Vehiculos_de_cortesia': 'Vehículos de cortesía',
        'Chatarra': 'Chatarra'
      }
    };
  },
  mounted() {
    this.cargarVehiculosEnDeposito();
    this.cargarTarifas();
    this.cargarDatosAgente();
  },
  methods: {
    async cargarVehiculosEnDeposito() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$parent.isAuthenticated = false;
        this.$router.push('/login');
        return;
      }

      try {
        const response = await fetch('/api/vehiculos', {
          headers: {
            'Authorization': `Bearer ${token}`,
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
          throw new Error('Error al cargar vehículos');
        }

        const vehiculos = await response.json();
        // Filtrar solo los vehículos que están en depósito
        this.vehiculosEnDeposito = vehiculos.filter(v => v.estado === 'En depósito');
      } catch (error) {
        console.error('Error al cargar vehículos:', error);
      }
    },
    onVehiculoChange() {
      if (this.selectedVehiculo) {
        this.formData.vehiculo_id = this.selectedVehiculo.id;
        if (this.formData.fecha) {
          this.calcularImportes();
        }
      } else {
        this.resetImportes();
      }
    },
    watch: {
      'formData.fecha': function(newVal) {
        if (newVal && this.selectedVehiculo) {
          this.calcularImportes();
        }
      }
    },
    async cargarDatosAgente() {
      const token = localStorage.getItem('token');
      if (!token) return;

      try {
        const response = await fetch('/api/user', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });

        if (!response.ok) throw new Error('Error al cargar datos del usuario');
        const userData = await response.json();
        
        // Asegurarse de que el nombre del agente se establece correctamente
        if (userData && userData.nombre) {
          this.formData.agente = userData.nombre;
          console.log('Datos del agente cargados:', userData.nombre);
        } else {
          console.error('No se pudo obtener el nombre del agente');
        }
      } catch (error) {
        console.error('Error al cargar datos del agente:', error);
      }
    },

    async cargarTarifas() {
      const token = localStorage.getItem('token');
      if (!token) return;

      try {
        const response = await fetch('/api/tarifas', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });

        if (!response.ok) throw new Error('Error al cargar tarifas');
        const tarifasData = await response.json();
        
        // Si la API devuelve un array de tarifas, lo usamos
        if (Array.isArray(tarifasData) && tarifasData.length > 0) {
          this.tarifas = tarifasData;
        }
        // Si no hay tarifas en la API, usamos las predefinidas
      } catch (error) {
        console.error('Error al cargar tarifas:', error);
        // Mantenemos las tarifas predefinidas en caso de error
      }
    },
    calcularImportes() {
      if (!this.selectedVehiculo || !this.formData.fecha) return;

      const tipoVehiculo = this.selectedVehiculo.tipovehiculo;
      let tarifa;
      
      // Verificar si tarifas es un array (datos de API) o un objeto (datos predefinidos)
      if (Array.isArray(this.tarifas)) {
        tarifa = this.tarifas.find(t => t.tipovehiculo === tipoVehiculo);
      } else {
        // Usar las tarifas predefinidas si no hay datos de la API
        tarifa = this.tarifas[tipoVehiculo];
        if (tarifa) {
          // Adaptar el formato para que coincida con el esperado
          tarifa = {
            tipovehiculo: tipoVehiculo,
            costobase: tarifa.costoBase,
            horasgratis: tarifa.horasGratis,
            costoporhora: tarifa.costoPorHora
          };
        }
      }

      if (!tarifa) {
        console.error('Tarifa no encontrada para el tipo de vehículo:', tipoVehiculo);
        return;
      }

      // Calcular importe de retirada (costo base)
      this.formData.importeretirada = parseFloat(tarifa.costobase || tarifa.costoBase);

      // Calcular importe de depósito basado en el tiempo real de estancia
      const fechaEntrada = new Date(this.selectedVehiculo.fechaentrada);
      const fechaSalida = new Date(this.formData.fecha);
      const horasEnDeposito = Math.ceil((fechaSalida - fechaEntrada) / (1000 * 60 * 60));
      
      // Restar las horas gratis según la tarifa
      const horasGratis = parseInt(tarifa.horasgratis || tarifa.horasGratis);
      const horasFacturables = Math.max(0, horasEnDeposito - horasGratis);
      const costoPorHora = parseFloat(tarifa.costoporhora || tarifa.costoPorHora);
      this.formData.importedeposito = horasFacturables * costoPorHora;

      // Calcular total
      this.formData.total = this.formData.importeretirada + this.formData.importedeposito;
      
      console.log('Cálculo de importes:', {
        tipoVehiculo,
        fechaEntrada: fechaEntrada.toISOString(),
        fechaSalida: fechaSalida.toISOString(),
        horasEnDeposito,
        horasGratis,
        horasFacturables,
        costoPorHora,
        importeRetirada: this.formData.importeretirada,
        importeDeposito: this.formData.importedeposito,
        total: this.formData.total
      });
    },
    resetImportes() {
      this.formData.importeretirada = 0;
      this.formData.importedeposito = 0;
      this.formData.total = 0;
    },
    getTipoVehiculoDisplay(tipo) {
      return this.tiposVehiculo[tipo] || tipo;
    },
    formatFecha(fecha) {
      if (!fecha) return 'N/A';
      const date = new Date(fecha);
      return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    calcularDiasEnDeposito(fechaEntrada) {
      if (!fechaEntrada) return 0;

      const fechaInicio = new Date(fechaEntrada);
      const fechaActual = new Date();

      // Calcular la diferencia en milisegundos
      const diferencia = fechaActual - fechaInicio;

      // Convertir a días (1000ms * 60s * 60min * 24h)
      const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24));

      return dias;
    },
    onClose() {
      this.$emit('close');
    },
    validateNIF(nif) {
      const nifRegex = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;
      const nieRegex = /^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;
      return nifRegex.test(nif) || nieRegex.test(nif);
    },
    validateDate(date) {
      const currentDate = new Date();
      const entryDate = this.selectedVehiculo ? new Date(this.selectedVehiculo.fechaentrada) : null;
      return date && (!entryDate || date > entryDate) && date <= currentDate;
    },
    validateForm() {
      this.formSubmitted = true;
      let isValid = true;
      
      // Reset all error messages
      Object.keys(this.errors).forEach(key => {
        this.errors[key] = '';
      });

      if (!this.selectedVehiculo) {
        this.errors.vehiculo = 'Debe seleccionar un vehículo.';
        isValid = false;
      }

      if (!this.formData.nombre || this.formData.nombre.length < 3) {
        this.errors.nombre = 'El nombre debe tener al menos 3 caracteres.';
        isValid = false;
      }

      if (!this.formData.nif || !this.validateNIF(this.formData.nif)) {
        this.errors.nif = 'El NIF/NIE introducido no es válido.';
        isValid = false;
      }

      if (!this.formData.domicilio || this.formData.domicilio.length < 5) {
        this.errors.domicilio = 'La dirección debe tener al menos 5 caracteres.';
        isValid = false;
      }

      if (!this.formData.poblacion || this.formData.poblacion.length < 2) {
        this.errors.poblacion = 'La población debe tener al menos 2 caracteres.';
        isValid = false;
      }

      if (!this.formData.provincia || this.formData.provincia.length < 2) {
        this.errors.provincia = 'La provincia debe tener al menos 2 caracteres.';
        isValid = false;
      }

      if (!this.formData.permiso) {
        this.errors.permiso = 'Debe seleccionar un tipo de permiso de conducir.';
        isValid = false;
      }

      if (!this.formData.fecha || !this.validateDate(this.formData.fecha)) {
        this.errors.fecha = 'La fecha de retirada debe ser posterior a la fecha de entrada y no puede ser futura.';
        isValid = false;
      }

      if (!this.formData.agente) {
        this.errors.agente = 'El campo agente es obligatorio.';
        isValid = false;
      }

      if (!this.formData.opcionespago) {
        this.errors.opcionespago = 'Debe seleccionar una forma de pago.';
        isValid = false;
      }

      return isValid;
    },
    onSave() {
      if (!this.validateForm()) {
        return;
      }

      // Preparar datos para guardar
      const retiradaData = {
        vehiculo_id: this.selectedVehiculo.id,
        fecha: this.formData.fecha,
        nombre: this.formData.nombre,
        nif: this.formData.nif,
        domicilio: this.formData.domicilio,
        poblacion: this.formData.poblacion,
        provincia: this.formData.provincia,
        permiso: this.formData.permiso,
        agente: this.formData.agente,
        importeretirada: this.formData.importeretirada,
        importedeposito: this.formData.importedeposito,
        total: this.formData.total,
        opcionespago: this.formData.opcionespago,
      };
      
      // Crear el objeto vehiculoData solo con los campos necesarios para el backend
      const vehiculoData = {
        id: this.selectedVehiculo.id,
        fechaentrada: this.selectedVehiculo.fechaentrada,
        fechasalida: new Date(),
        lugar: this.selectedVehiculo.lugar || '',
        direccion: this.selectedVehiculo.direccion || '',
        agente: this.selectedVehiculo.agente || this.formData.agente,
        matricula: this.selectedVehiculo.matricula,
        marca: this.selectedVehiculo.marca,
        modelo: this.selectedVehiculo.modelo,
        color: this.selectedVehiculo.color,
        tipovehiculo: this.selectedVehiculo.tipovehiculo,
        estado: 'Retirado',
        motivo: this.selectedVehiculo.motivo || '',
        grua: this.selectedVehiculo.grua || ''
      };
      
      // Guardamos las observaciones en el vehículo si existen
      if (this.selectedVehiculo.observaciones) {
        vehiculoData.observaciones = this.selectedVehiculo.observaciones;
      }

      this.guardarRetirada(retiradaData, vehiculoData);
    },
    async guardarRetirada(retiradaData, vehiculoData) {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$parent.isAuthenticated = false;
        this.$router.push('/login');
        return;
      }

      try {
        // Guardar la retirada
        const retiradaResponse = await fetch('/api/retiradas', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          },
          body: JSON.stringify(retiradaData)
        });

        if (!retiradaResponse.ok) {
          throw new Error('Error al guardar la retirada');
        }

        // Actualizar el vehículo
        const vehiculoResponse = await fetch(`/api/vehiculos/${vehiculoData.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          },
          body: JSON.stringify(vehiculoData)
        });

        if (!vehiculoResponse.ok) {
          throw new Error('Error al actualizar el vehículo');
        }

        // Emitir evento de guardado exitoso
        this.$emit('save');
        this.$emit('update:visible', false);

        // Mostrar mensaje de éxito
        alert('Retirada procesada correctamente');
      } catch (error) {
        console.error('Error al procesar la retirada:', error);
        alert(`Error: ${error.message}`);
      }
    }
  }
};
</script>
<style scoped>
.retirada-dialog {
  font-family: var(--font-family);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  padding: 1rem;
}

.section-header {
  grid-column: 1 / -1;
  margin: 1.5rem 0 1rem;
  border-bottom: 2px solid var(--primary-color);
  padding-bottom: 0.5rem;
}

.section-header h3 {
  color: var(--primary-color);
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: var(--text-color);
}

.form-control {
  width: 100%;
  transition: all 0.3s ease;
}

:deep(.p-dropdown),
:deep(.p-calendar),
:deep(.p-inputtext),
:deep(.p-inputnumber) {
  width: 100%;
}

:deep(.p-dropdown-panel) {
  max-width: 100%;
}

.vehiculo-option {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.vehiculo-option .matricula {
  font-weight: 600;
  color: var(--primary-color);
}

.vehiculo-option .details {
  font-size: 0.9rem;
  color: var(--text-secondary-color);
}

.vehiculo-details {
  background: var(--surface-100);
  border-radius: 6px;
  padding: 1rem;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 0.75rem;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.detail-item .label {
  font-size: 0.875rem;
  color: var(--text-color-secondary);
}

.detail-item .value {
  font-weight: 500;
  color: var(--text-color);
}

.no-vehiculo-selected {
  color: var(--text-color-secondary);
  font-style: italic;
  padding: 1rem;
  background: var(--surface-100);
  border-radius: 6px;
  text-align: center;
}

.p-error {
  color: var(--red-500);
  font-size: 0.875rem;
  margin-top: 0.25rem;
  display: block;
}

.dialog-footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--surface-200);
}

:deep(.p-button) {
  transition: all 0.3s ease;
}

:deep(.p-button-text) {
  color: var(--text-color-secondary);
}

:deep(.p-button-text:hover) {
  background: var(--surface-100);
}

:deep(.p-button-primary) {
  background: var(--primary-color);
  border-color: var(--primary-color);
}

:deep(.p-button-primary:hover) {
  background: var(--primary-600);
  border-color: var(--primary-600);
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .vehiculo-details {
    grid-template-columns: 1fr;
  }
}
</style>