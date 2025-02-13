<template>
  <div id="app">
    <!-- Encabezado -->
    <header class="header">
      <div class="header-content">
        <h1>Sistema de Gestión de Retiradas de Vehículos</h1>
        <nav>
          <ul class="nav-links">
            <li v-if="!isAuthenticated">
              <router-link to="/login" class="nav-link">Iniciar Sesión</router-link>
            </li>
            <li v-if="isAuthenticated">
              <router-link to="/vehiculos" class="nav-link">Gestión de Vehículos</router-link>
            </li>
            <li v-if="isAuthenticated">
              <router-link to="/retiradas" class="nav-link">Gestión de Retiradas</router-link>
            </li>
            <li v-if="isAuthenticated">
              <a href="#" @click="logout" class="nav-link">Cerrar Sesión</a>
            </li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- Contenido Principal -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- Pie de Página -->
    <footer class="footer">
      <p>&copy; {{ currentYear }} Sistema de Gestión de Retiradas de Vehículos</p>
    </footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isAuthenticated: false, // Estado de autenticación del usuario
    };
  },
  computed: {
    currentYear() {
      return new Date().getFullYear(); // Año actual para el pie de página
    },
  },
  methods: {
    logout() {
      // Limpiar token y redirigir al login
      localStorage.removeItem("token");
      this.isAuthenticated = false;
      this.$router.push("/login");
    },
    checkAuthentication() {
      // Verificar si el usuario está autenticado
      const token = localStorage.getItem("token");
      this.isAuthenticated = !!token;
    },
  },
  created() {
    // Verificar autenticación al cargar la aplicación
    this.checkAuthentication();
  },
};
</script>

<style scoped>
/* Estilos Generales */
#app {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  text-align: center;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f8f9fa;
}

/* Header */
.header {
  background-color: green;
  color: white;
  padding: 1rem 2rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header h1 {
  margin: 0;
  font-size: 24px;
  font-weight: 600;
}

.nav-links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  gap: 1rem;
}

.nav-link {
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.nav-link:hover {
  color: #ffcc00;
  /* Cambio de color al pasar el ratón */
}

/* Main Content */
.main-content {
  flex: 1;
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 12px;
  margin: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Footer */
.footer {
  background-color: green;
  color: white;
  padding: 1rem;
  text-align: center;
  font-size: 0.9rem;
  box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
}
</style>