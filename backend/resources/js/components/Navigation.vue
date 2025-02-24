<template>
  <nav class="main-nav">
    <div class="nav-brand">
      <h1>Sistema de Grúas</h1>
    </div>
    <div class="nav-links">
      <router-link to="/dashboard" class="nav-item">
        <i class="pi pi-home"></i>
        <span>Dashboard</span>
      </router-link>
      <router-link to="/users" class="nav-item">
        <i class="pi pi-users"></i>
        <span>Usuarios</span>
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
        <span>Cerrar Sesión</span>
      </button>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation',
  methods: {
    logout() {
      // Implement logout logic here
      this.$router.push('/login');
    }
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
  border-color: white;
}

@media (max-width: 768px) {
  .nav-links {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background-color: var(--primary-color);
    flex-direction: column;
    padding: 1rem;
  }

  .nav-links.show {
    display: flex;
  }

  .nav-item {
    width: 100%;
  }
}
</style>