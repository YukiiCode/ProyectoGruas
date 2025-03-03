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

      <!-- Modal de confirmación para eliminar usuario -->
      <div class="modal-content" v-if="showDeleteModal">
        <h3>Eliminar Usuario</h3>
        <p>¿Está seguro que desea eliminar al usuario <strong>{{ userToDelete?.nombre }}</strong>?</p>
        <p class="warning-text">Esta acción no se puede deshacer.</p>
        <div class="form-actions">
          <button @click="deleteUser()" class="btn btn-danger">
            <i class="pi pi-trash"></i>
            Eliminar
          </button>
          <button type="button" class="btn btn-secondary" @click="closeModal">
            <i class="pi pi-times"></i>
            Cancelar
          </button>
        </div>
      </div>
    </div>

    <!-- Loading spinner -->
    <div class="loading-container" v-if="loading">
      <div class="loading-spinner">
        <i class="pi pi-spin pi-spinner"></i>
        <span>Cargando usuarios...</span>
      </div>
    </div>

    <div class="table-container" v-if="!loading">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in paginatedUsuarios" :key="user.id">
            <td>{{ user.nombre }}</td>
            <td>{{ user.usuario }}</td>
            <td><span class="role-badge">{{ user.rol }}</span></td>
            <td>
              <button @click="editUser(user)" class="btn btn-icon btn-edit">
                <i class="pi pi-pencil"></i>
              </button>
              <button @click="confirmDelete(user)" class="btn btn-icon btn-danger">
                <i class="pi pi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="usuariosFiltrados.length === 0" class="no-results">
        <i class="pi pi-exclamation-circle"></i>
        <p>No se encontraron usuarios{{ filtro ? ' con el criterio "' + filtro + '"' : '' }}</p>
      </div>
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

    <!-- Modal para crear/editar usuario -->
    <div class="modal" v-if="showCreateModal || showEditModal || showDeleteModal">
      <div class="modal-content" v-if="showCreateModal || showEditModal">
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

          <!-- Campo de contraseña para edición (opcional) -->
          <div class="form-group" v-if="showEditModal">
            <label for="password">Cambiar Contraseña (opcional)</label>
            <input
              id="password"
              v-model="formData.password"
              type="password"
              class="form-control"
              placeholder="Dejar en blanco para mantener la contraseña actual"
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

      <!-- Modal de confirmación para eliminar usuario -->
      <div class="modal-content" v-if="showDeleteModal">
        <h3>Eliminar Usuario</h3>
        <p>¿Está seguro que desea eliminar al usuario <strong>{{ userToDelete?.nombre }}</strong>?</p>
        <p class="warning-text">Esta acción no se puede deshacer.</p>
        <div class="form-actions">
          <button @click="deleteUser()" class="btn btn-danger">
            <i class="pi pi-trash"></i>
            Eliminar
          </button>
          <button type="button" class="btn btn-secondary" @click="closeModal">
            <i class="pi pi-times"></i>
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  created() {
    const token = localStorage.getItem('token');
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
  },
  mounted() {
    this.loadUsers();
  },
  name: 'Users',
  data() {
    return {
      users: [],
      filtro: '',
      loading: false,
      showCreateModal: false,
      showEditModal: false,
      showDeleteModal: false,
      userToDelete: null,
      currentPage: 1,
      itemsPerPage: 10,
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
    },
    totalPages() {
      return Math.ceil(this.usuariosFiltrados.length / this.itemsPerPage);
    },
    paginatedUsuarios() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.usuariosFiltrados.slice(start, end);
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
      this.formData = { ...user, password: '' };
      this.showEditModal = true;
    },
    confirmDelete(user) {
      this.userToDelete = user;
      this.showDeleteModal = true;
    },
    async deleteUser() {
      try {
        await axios.delete(`/api/users/${this.userToDelete.id}`);
        this.closeModal();
        await this.loadUsers();
      } catch (error) {
        console.error('Error al eliminar usuario:', error);
      }
    },
    async handleSubmit() {
      try {
        if (this.showEditModal) {
          // Si estamos editando y no hay contraseña, eliminamos el campo para no actualizarlo
          const userData = {...this.formData};
          if (!userData.password) {
            delete userData.password;
          }
          await axios.put(`/api/users/${this.formData.id}`, userData);
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
      this.showDeleteModal = false;
      this.userToDelete = null;
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

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
  background: white;
  border-radius: 8px;
  box-shadow: var(--shadow);
}

.loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.loading-spinner i {
  font-size: 2.5rem;
  color: var(--primary-color);
}

.loading-spinner span {
  font-size: 1rem;
  color: #666;
}

.role-badge {
  background-color: var(--primary-color);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.9em;
}

.btn-danger {
  background-color: #f44336;
  color: white;
}

.warning-text {
  color: #f44336;
  font-weight: bold;
  margin-bottom: 1rem;
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
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
  gap: 0.25rem;
  padding: 0.5rem;
}

.pagination-button {
  min-width: 2rem;
  height: 2rem;
  background-color: transparent;
  border: 1px solid #e2e8f0;
  color: #64748b;
  padding: 0.25rem;
  margin: 0;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.8rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pagination-button:hover {
  background-color: #f1f5f9;
  border-color: #cbd5e1;
  color: #475569;
}

.pagination-button.active {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
  color: white;
  font-weight: 500;
}

.pagination-button:disabled {
  opacity: 0.4;
  cursor: not-allowed;
  background-color: #f8fafc;
}
</style>