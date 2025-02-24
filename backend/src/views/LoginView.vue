<template>
  <div class="login-page">
    <div class="login-container">
      <div class="login-header">
        <img src="@/assets/logo.png" alt="Logo Grúa Municipal" class="logo">
        <h1>Sistema de Gestión de Grúas</h1>
      </div>
      <div class="login-form">
        <h2>Iniciar Sesión</h2>
        <form @submit.prevent="handleLogin">
          <div class="form-group">
            <label for="username">Usuario</label>
            <input 
              type="text" 
              id="username"
              v-model="username"
              required
              placeholder="Ingrese su usuario"
            >
          </div>
          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="password-input">
              <input 
                :type="showPassword ? 'text' : 'password'"
                id="password"
                v-model="password"
                required
                placeholder="Ingrese su contraseña"
              >
              <button 
                type="button" 
                class="toggle-password"
                @click="showPassword = !showPassword"
              >
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
            </div>
          </div>
          <button type="submit" class="btn-login">
            Iniciar Sesión
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return {
      username: '',
      password: '',
      showPassword: false
    }
  },
  methods: {
    handleLogin() {
      // TODO: Implement login logic
      console.log('Login attempt:', { username: this.username, password: this.password })
    }
  }
}
</script>

<style>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  padding: 20px;
}

.login-container {
  background: white;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 400px;
  padding: 2rem;
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.login-header .logo {
  width: 120px;
  margin-bottom: 1rem;
}

.login-header h1 {
  color: #1e3c72;
  font-size: 1.5rem;
  margin: 0;
}

.login-form h2 {
  color: #2a5298;
  font-size: 1.25rem;
  margin-bottom: 1.5rem;
  text-align: center;
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #4a5568;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 1rem;
}

.form-group input:focus {
  outline: none;
  border-color: #2a5298;
}

.password-input {
  position: relative;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #718096;
  cursor: pointer;
  padding: 0;
}

.toggle-password:hover {
  color: #2a5298;
}

.btn-login {
  width: 100%;
  padding: 0.75rem;
  background: #2a5298;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
}

.btn-login:hover {
  background: #1e3c72;
}
</style>