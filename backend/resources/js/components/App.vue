<template>
  <div id="app">
    <Navigation v-if="isAuthenticated" />
    <!-- Contenido principal -->
    <main>
      <router-view></router-view>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navigation from './Navigation.vue';
import Footer from './Footer.vue';

export default {
  name: 'App',
  components: {
    Navigation,
    Footer
  },
  data() {
    return {
      isAuthenticated: false,
      _initialAuthCheck: true,
      _forceAuthCheck: false
    }
  },
  methods: {
    async logout() {
      try {
        const token = localStorage.getItem('token');
        if (token) {
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          await axios.post('http://localhost:8000/api/logout');
        }
        
        // Clear all authentication data
        localStorage.clear();
        this.isAuthenticated = false;
        delete axios.defaults.headers.common['Authorization'];
        
        // Force navigation to login and reload
        await this.$router.push('/login');
        window.location.reload(); // Force a full page reload to reset all states
      } catch (error) {
        console.error('Logout error:', error);
        // Even if the API call fails, clear local data
        localStorage.clear();
        this.isAuthenticated = false;
        delete axios.defaults.headers.common['Authorization'];
        await this.$router.push('/login');
        window.location.reload();
      }
    },
    async checkAuth() {
      const token = localStorage.getItem('token');
      if (token) {
        try {
          // Set the Authorization header for all subsequent requests
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          const response = await axios.get('http://localhost:8000/api/user');
          const userData = response.data;
          this.isAuthenticated = true;
          // Store user role in localStorage
          localStorage.setItem('userRole', userData.rol ? userData.rol.toLowerCase() : '');
        } catch (error) {
          console.error('Auth check error:', error);
          localStorage.removeItem('token');
          localStorage.removeItem('userRole');
          delete axios.defaults.headers.common['Authorization'];
          this.isAuthenticated = false;
          if (this.$route.path !== '/login') {
            this.$router.push('/login');
          }
        }
      } else {
        localStorage.removeItem('userRole');
        delete axios.defaults.headers.common['Authorization'];
        this.isAuthenticated = false;
        if (this.$route.path !== '/login') {
          this.$router.push('/login');
        }
      }
    },
  },
  created() {
    this.checkAuth();
  },
  watch: {
    $route: {
      immediate: true,
      handler(to, from) {
        // Check authentication on every route change
        this.checkAuth();
        this._initialAuthCheck = false;
        this._forceAuthCheck = false;
      }
    }
  }
};
</script>

<style>
/* Global styles */
:root {
  --primary-color: #2c3e50;
  --secondary-color: #3498db;
  --accent-color: #e74c3c;
  --background-color: #f8f9fa;
  --text-color: #333;
}

body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: var(--background-color);
  color: var(--text-color);
}

.header {
  background-color: var(--primary-color);
  color: white;
  padding: 1rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header h1 {
  font-size: 1.5rem;
  margin: 0;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 20px;
  margin: 0;
  padding: 0;
}

.nav-links a {
  color: white;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.nav-links a:hover {
  background-color: rgba(255,255,255,0.1);
}

main {
  max-width: 1200px;
  margin: 20px auto;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Common components styles */
.btn-primary {
  background-color: var(--secondary-color);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #2980b9;
}

.form-control {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 10px;
}

.table-container {
  overflow-x: auto;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

.custom-table th,
.custom-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.custom-table th {
  background-color: #f8f9fa;
  font-weight: 600;
}

.custom-table tr:hover {
  background-color: #f5f5f5;
}
</style>