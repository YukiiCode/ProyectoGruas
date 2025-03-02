<template>
  <div class="login-page">
    <div class="login-container">
      <div class="login-header">
        <h1>Sistema de Gestión de Grúas</h1>
      </div>
      <div class="login-form">
        <form @submit.prevent="login">
          <div class="form-group">
            <label for="usuario">Usuario</label>
            <div class="input-container">
              <i class="pi pi-user"></i>
              <input 
                id="usuario" 
                v-model="usuario" 
                type="text" 
                required 
                placeholder="Ingrese su usuario"
                class="form-control"
              />
            </div>
          </div>
          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-container">
              <i class="pi pi-lock"></i>
              <input 
                id="password" 
                v-model="password" 
                :type="showPassword ? 'text' : 'password'" 
                required 
                placeholder="Ingrese su contraseña"
                class="form-control"
              />
              <button 
                type="button" 
                class="toggle-password"
                @click="showPassword = !showPassword"
              >
                <i :class="showPassword ? 'pi pi-eye-slash' : 'pi pi-eye'"></i>
              </button>
            </div>
          </div>
          <div class="error-container" v-if="error">
            <i class="pi pi-exclamation-circle"></i>
            <span>{{ error }}</span>
          </div>
          <button type="submit" class="btn btn-primary login-button">
            <i class="pi pi-sign-in"></i>
            Iniciar Sesión
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { setAuthToken } from '../bootstrap';

export default {
  name: 'Login',
  data() {
    return {
      usuario: '',
      password: '',
      error: '',
      showPassword: false
    }
  },
  methods: {
    async login() {
      try {
        const response = await fetch('/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            usuario: this.usuario,
            password: this.password
          })
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Login failed');
        }

        const data = await response.json();
        localStorage.setItem('token', data.token);
        // Set the auth token for axios requests
        setAuthToken(data.token);
        this.$parent.isAuthenticated = true;
        this.$router.push('/home');
      } catch (err) {
        console.error('Login error:', err);
        this.error = 'Credenciales incorrectas';
      }
    }
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--background-color);
}

.login-container {
  width: 100%;
  max-width: 400px;
  padding: 2rem;
  background: white;
  border-radius: 12px;
  box-shadow: var(--shadow);
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.login-header h1 {
  color: var(--primary-color);
  font-size: 1.75rem;
  margin: 0;
}

.form-group {
  margin-bottom: 1.5rem;
}

.input-container {
  position: relative;
  display: flex;
  align-items: center;
}

.input-container i {
  position: absolute;
  left: 12px;
  color: var(--text-color);
  opacity: 0.5;
}

.input-container input {
  padding-left: 40px;
}

.toggle-password {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  color: var(--text-color);
  opacity: 0.5;
  cursor: pointer;
  padding: 0;
}

.toggle-password:hover {
  opacity: 0.8;
}

.error-container {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px;
  background-color: var(--error-color);
  color: white;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.error-container i {
  font-size: 1.1em;
}

.login-button {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px;
  font-size: 1rem;
}
</style>