<template>
  <nav class="main-nav">
    <div class="nav-brand">
      <h1>Sistema de Grúas</h1>
    </div>
    <div class="nav-links">
      <router-link to="/home" class="nav-item">
        <i class="pi pi-home"></i>
        <span>Dashboard</span>
      </router-link>
      <router-link v-if="isAdmin" to="/users" class="nav-item">
        <i class="pi pi-users"></i>
        <span>Usuarios</span>
      </router-link>
      <router-link v-if="isAdmin" to="/logs" class="nav-item">
        <i class="pi pi-list"></i>
        <span>Logs</span>
      </router-link>
      <router-link to="/vehiculos" class="nav-item">
        <i class="pi pi-car"></i>
        <span>Vehículos</span>
      </router-link>
      <router-link to="/retiradas" class="nav-item">
        <i class="pi pi-truck"></i>
        <span>Retiradas</span>
      </router-link>
    </div>
    <div class="nav-user">
      <button @click="logout" class="btn-logout">
        <i class="pi pi-sign-out"></i>
        <span>{{ logoutButtonText }}</span>
      </button>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation',
  data() {
    return {
      isAdmin: false
    }
  },
  computed: {
    isAuthenticated() {
      return this.$parent.isAuthenticated;
    },
    logoutButtonText() {
      return this.isAuthenticated ? 'Cerrar Sesión' : 'Iniciar Sesión';
    }
  },
  watch: {
    '$route': {
      immediate: true,
      handler() {
        this.checkUserRole();
      }
    }
  },
  created() {
    this.checkUserRole();
    window.addEventListener('storage', this.handleStorageChange);
  },
  beforeDestroy() {
    window.removeEventListener('storage', this.handleStorageChange);
  },
  methods: {
    handleStorageChange(event) {
      if (event.key === 'token' || event.key === 'userRole') {
        this.checkUserRole();
      }
    },
    async logout() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }

      try {
        // Set the Authorization header before making the logout request
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        await axios.post('/api/logout');
        
        // Clear all authentication data
        localStorage.clear();
        this.isAdmin = false;
        this.$parent.isAuthenticated = false;
        delete axios.defaults.headers.common['Authorization'];
        
        // Force navigation to login
        await this.$router.push('/login');
        window.location.reload(); // Force a full page reload to reset all states
      } catch (error) {
        console.error('Error during logout:', error);
        // Even if the API call fails, clear local data
        localStorage.clear();
        this.isAdmin = false;
        this.$parent.isAuthenticated = false;
        delete axios.defaults.headers.common['Authorization'];
        await this.$router.push('/login');
        window.location.reload();
      }
    },
    async checkUserRole() {
      const token = localStorage.getItem('token');
      const storedRole = localStorage.getItem('userRole');
      
      if (!token) {
        this.isAdmin = false;
        this.$parent.isAuthenticated = false;
        localStorage.removeItem('userRole');
        return;
      }

      try {
        const response = await axios.get('/api/user');
        const userData = response.data;
        const userRole = userData.rol;
        this.isAdmin = userRole && userRole.toLowerCase() === 'admin';
        this.$parent.isAuthenticated = true;
        if (userRole) {
          localStorage.setItem('userRole', userRole.toLowerCase());
          console.log('Current user role:', userRole.toLowerCase());
        }
      } catch (error) {
        console.error('Error checking user role:', error);
        this.isAdmin = false;
        this.$parent.isAuthenticated = false;
        localStorage.removeItem('userRole');
      }
    }
  },
  mounted() {
    this.checkUserRole();
  }
};
</script>

<style scoped>
.main-nav {
  background-color: var(--primary-color);
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-brand h1 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: white;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.nav-item:hover,
.nav-item.router-link-active {
  background-color: rgba(255, 255, 255, 0.1);
}

.nav-item i {
  font-size: 1.2rem;
}

.btn-logout {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: none;
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-logout:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.5);
}
</style>