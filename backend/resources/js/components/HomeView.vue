<template>
  <div class="home-container">
    <div class="welcome-section">
      <h2>Bienvenido, {{ userName }}</h2>
      <p class="role-badge">{{ userRole }}</p>
    </div>

    <div class="dashboard-grid">
      <div class="dashboard-card" @click="$router.push('/vehiculos')">
        <i class="fas fa-car"></i>
        <h3>Gestión de Vehículos</h3>
        <p>Administrar vehículos retirados</p>
      </div>

      <div class="dashboard-card" @click="$router.push('/retiradas')">
        <i class="fas fa-truck-pickup"></i>
        <h3>Gestión de Retiradas</h3>
        <p>Ver y gestionar retiradas</p>
      </div>

      <div class="dashboard-card stats">
        <i class="fas fa-chart-bar"></i>
        <h3>Estadísticas</h3>
        <div class="stats-info">
          <p>Retiradas hoy: {{ todayRetiradas }}</p>
          <p>Total vehículos: {{ totalVehiculos }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeView',
  data() {
    return {
      userName: 'Operador',
      userRole: 'Operario de Grúa',
      todayRetiradas: 0,
      totalVehiculos: 0
    }
  },
  async created() {
    await this.loadStatistics();
  },
  methods: {
    async loadStatistics() {
      try {
        // Obtener el token de autenticación
        const token = localStorage.getItem('token');
        if (!token) return;
        
        // Realizar llamadas a la API con el token de autenticación
        const retiradas = await fetch('http://localhost:8000/api/retiradas', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });
        const vehiculos = await fetch('http://localhost:8000/api/vehiculos', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });
        
        if (!retiradas.ok || !vehiculos.ok) {
          console.error('Error al obtener datos');
          return;
        }
        
        const retiradasData = await retiradas.json();
        const vehiculosData = await vehiculos.json();

        this.todayRetiradas = retiradasData.length;
        this.totalVehiculos = vehiculosData.length;
      } catch (error) {
        console.error('Error al cargar estadísticas:', error);
      }
    }
  }
}
</script>

<style scoped>
.home-container {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.welcome-section {
  text-align: center;
  margin-bottom: 3rem;
}

.welcome-section h2 {
  font-size: 2.5rem;
  color: var(--primary-color);
  margin-bottom: 0.5rem;
}

.role-badge {
  display: inline-block;
  padding: 0.5rem 1rem;
  background-color: var(--secondary-color);
  color: white;
  border-radius: 20px;
  font-size: 1rem;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.dashboard-card {
  background: white;
  border-radius: 10px;
  padding: 2rem;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  cursor: pointer;
}

.dashboard-card:hover {
  transform: translateY(-5px);
}

.dashboard-card i {
  font-size: 2.5rem;
  color: var(--secondary-color);
  margin-bottom: 1rem;
}

.dashboard-card h3 {
  color: var(--primary-color);
  margin-bottom: 1rem;
}

.dashboard-card.stats {
  cursor: default;
}

.dashboard-card.stats:hover {
  transform: none;
}

.stats-info p {
  margin: 0.5rem 0;
  font-size: 1.1rem;
  color: var(--primary-color);
}
</style>