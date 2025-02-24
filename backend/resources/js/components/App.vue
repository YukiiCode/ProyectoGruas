<template>
  <div id="app">
    <!-- Encabezado -->
    <header class="header">
      <div class="header-content">
        <h1>Sistema de Gestión de Retiradas de Vehículos</h1>
        <nav>
          <ul class="nav-links">
            <li v-if="!isAuthenticated">
              <router-link to="/login">Iniciar Sesión</router-link>
            </li>
            <li v-if="isAuthenticated">
              <router-link to="/vehiculos">Gestión de Vehículos</router-link>
            </li>
            <li v-if="isAuthenticated">
              <router-link to="/retiradas">Gestión de Retiradas</router-link>
            </li>
            <li v-if="isAuthenticated">
              <a href="#" @click="logout">Cerrar Sesión</a>
            </li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- Contenido principal -->
    <main>
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      isAuthenticated: false
    }
  },
  methods: {
    logout() {
      localStorage.removeItem('token');
      this.isAuthenticated = false;
      this.$router.push('/login');
    },
    checkAuth() {
      const token = localStorage.getItem('token');
      this.isAuthenticated = !!token;
    }
  },
  created() {
    this.checkAuth();
  }
}
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