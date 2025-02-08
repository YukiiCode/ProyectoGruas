<template>
  <div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form @submit.prevent="login" class="login-form">
      <div class="form-group">
        <label for="usuario">Usuario:</label>
        <input 
          id="usuario" 
          v-model="usuario" 
          type="text" 
          required 
          placeholder="Ingrese su usuario"
        />
      </div>
      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input 
          id="password" 
          v-model="password" 
          type="password" 
          required 
          placeholder="Ingrese su contraseña"
        />
      </div>
      <button type="submit" class="login-button">Iniciar Sesión</button>
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
/* Estilos generales del contenedor */
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 30px;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}

/* Título */
.login-container h2 {
  font-size: 24px;
  margin-bottom: 20px;
  color: #333333;
  font-weight: 600;
}

/* Formulario */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

/* Grupos de campos */
.form-group {
  text-align: left;
}

.form-group label {
  font-size: 14px;
  font-weight: 500;
  color: #555555;
  margin-bottom: 8px;
  display: block;
}

.form-group input {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  border: 1px solid #cccccc;
  border-radius: 8px;
  transition: border-color 0.3s ease;
}

.form-group input:focus {
  border-color: #007bff;
  outline: none;
}

/* Botón de inicio de sesión */
.login-button {
  padding: 12px;
  font-size: 16px;
  color: #ffffff;
  background-color: #007bff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-button:hover {
  background-color: #0056b3;
}

/* Mensaje de error */
.error {
  color: #e74c3c;
  font-size: 14px;
  margin-top: 10px;
  text-align: center;
}
</style>