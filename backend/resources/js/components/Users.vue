<template>
  <div class="container">
    <div class="page-header">
      <h2>Gestión de Usuarios</h2>
      <div class="header-actions">
        <button @click="showCreateModal = true" class="btn btn-primary">
          <i class="pi pi-plus"></i>
          Nuevo Usuario
        </button>
      </div>
      <div class="filter-container">
        <div class="search-box">
          <i class="pi pi-search"></i>
          <input 
            v-model="filtro" 
            type="text" 
            placeholder="Buscar por nombre o email..." 
            class="form-control"
          />
        </div>
      </div>
    </div>

    <div class="table-container" v-if="!loading">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in usuariosFiltrados" :key="user.id">
            <td>{{ user.nombre }}</td>
            <td>{{ user.usuario }}</td>
            <td><span class="role-badge">{{ user.rol }}</span></td>
            <td>
              <span :class="['status-badge', user.active ? 'status-active' : 'status-inactive']">
                {{ user.active ? 'Activo' : 'Inactivo' }}
              </span>
            </td>
            <td>
              <button @click="editUser(user)" class="btn btn-icon btn-edit">
                <i class="pi pi-pencil"></i>
              </button>
              <button @click="toggleUserStatus(user)" class="btn btn-icon" :class="user.active ? 'btn-danger' : 'btn-success'">
                <i class="pi" :class="user.active ? 'pi-times' : 'pi-check'"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal para crear/editar usuario -->
    <div class="modal" v-if="showCreateModal || showEditModal">
      <div class="modal-content">
        <h3>{{ showEditModal ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
        <form @submit.prevent="handleSubmit" class="user-form">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input
              id="nombre"
              v-model="formData.nombre"
              type="text"
              required
              class="form-control"
              placeholder="Nombre completo"
            />
          </div>

          <div class="form-group">
            <label for="usuario">Usuario</label>
            <input
              id="usuario"
              v-model="formData.usuario"
              type="text"
              required
              class="form-control"
              placeholder="Nombre de usuario"
              :disabled="showEditModal"
            />
          </div>

          <div class="form-group" v-if="!showEditModal">
            <label for="password">Contraseña</label>
            <input
              id="password"
              v-model="formData.password"
              type="password"
              required
              class="form-control"
              placeholder="Contraseña"
            />
          </div>

          <div class="form-group">
            <label for="rol">Rol</label>
            <select id="rol" v-model="formData.rol" required class="form-control">
              <option value="">Seleccione un rol</option>
              <option value="admin">Administrador</option>
              <option value="operador">Operador</option>
            </select>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="pi pi-save"></i>
              {{ showEditModal ? 'Actualizar' : 'Crear' }}
            </button>
            <button type="button" class="btn btn-secondary" @click="closeModal">
              <i class="pi pi-times"></i>
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Users',
  data() {
    return {
      users: [],
      filtro: '',
      loading: false,
      showCreateModal: false,
      showEditModal: false,
      formData: {
        nombre: '',
        usuario: '',
        password: '',
        rol: ''
      }
    };
  },
  computed: {
    usuariosFiltrados() {
      return this.users.filter(user =>
        user.nombre.toLowerCase().includes(this.filtro.toLowerCase()) ||
        user.usuario.toLowerCase().includes(this.filtro.toLowerCase())
      );
    }
  },
  mounted() {
    this.loadUsers();
  },
  methods: {
    async loadUsers() {
      this.loading = true;
      try {
        const response = await axios.get('/api/users');
        this.users = response.data;
      } catch (error) {
        console.error('Error al cargar usuarios:', error);
      } finally {
        this.loading = false;
      }
    },
    editUser(user) {
      this.formData = { ...user };
      this.showEditModal = true;
    },
    async toggleUserStatus(user) {
      try {
        await axios.patch(`/api/users/${user.id}/toggle-status`);
        await this.loadUsers();
      } catch (error) {
        console.error('Error al cambiar estado del usuario:', error);
      }
    },
    async handleSubmit() {
      try {
        if (this.showEditModal) {
          await axios.put(`/api/users/${this.formData.id}`, this.formData);
        } else {
          await axios.post('/api/users', this.formData);
        }
        this.closeModal();
        await this.loadUsers();
      } catch (error) {
        console.error('Error al guardar usuario:', error);
      }
    },
    closeModal() {
      this.showCreateModal = false;
      this.showEditModal = false;
      this.formData = {
        nombre: '',
        usuario: '',
        password: '',
        rol: ''
      };
    }
  }
};
</script>

<style scoped>
.container {
  padding: 2rem;
}

.page-header {
  margin-bottom: 2rem;
}

.header-actions {
  margin: 1rem 0;
}

.table-container {
  background: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
  overflow: hidden;
}

.role-badge {
  background-color: var(--primary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.9em;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.9em;
}

.status-active {
  background-color: #4CAF50;
  color: white;
}

.status-inactive {
  background-color: #f44336;
  color: white;
}

.btn-icon {
  padding: 0.5rem;
  margin: 0 0.25rem;
}

.btn-edit {
  background-color: var(--primary-color);
  color: white;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
}

.user-form {
  margin-top: 1rem;
}

.form-actions {
  margin-top: 2rem;
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}
</style>