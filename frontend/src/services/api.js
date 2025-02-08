
import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api', // URL del backend Laravel
  headers: {
    'Content-Type': 'application/json',
  },
});

// Agregar token de autenticación si existe
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default apiClient;