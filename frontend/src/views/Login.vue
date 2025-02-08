<template>
    <div class="login-container">
      <h2>Iniciar Sesión</h2>
      <form @submit.prevent="login">
        <div>
          <label>Usuario:</label>
          <input v-model="usuario" type="text" required />
        </div>
        <div>
          <label>Contraseña:</label>
          <input v-model="password" type="password" required />
        </div>
        <button type="submit">Iniciar Sesión</button>
        <p v-if="error" class="error">{{ error }}</p>
      </form>
    </div>
  </template>
  
  <script>
  import apiClient from '@/services/api';
  
  export default {
    data() {
      return {
        usuario: '',
        password: '',
        error: '',
      };
    },
    methods: {
      async login() {
        try {
          const response = await apiClient.post('/login', {
            usuario: this.usuario,
            password: this.password,
          });
          localStorage.setItem('token', response.data.token);
          this.$router.push('/vehiculos');
        } catch (err) {
          this.error = 'Credenciales incorrectas';
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
  }
  .error {
    color: red;
  }
  </style>