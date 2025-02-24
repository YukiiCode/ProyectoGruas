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
        <tr v-for="vehiculo in vehiculos" :key="vehiculo.id">
          <td><span class="matricula-badge">{{ vehiculo.matricula }}</span></td>
          <td>{{ vehiculo.marca }}</td>
          <td>{{ vehiculo.modelo }}</td>
          <td>
            <span class="color-dot" :style="{ backgroundColor: vehiculo.color }"></span>
            {{ vehiculo.color }}
          </td>
          <td><span class="tipo-badge">{{ vehiculo.tipovehiculo }}</span></td>
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
  emits: ['edit', 'delete']
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
  background-color: var(--secondary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.85em;
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
</style>
