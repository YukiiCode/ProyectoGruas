<template>
    <div>
      <h2>Retiradas Registradas</h2>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>NIF</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="retirada in retiradas" :key="retirada.id">
            <td>{{ retirada.nombre }}</td>
            <td>{{ retirada.nif }}</td>
            <td>{{ retirada.fecha }}</td>
            <td>{{ retirada.total }} €</td>
            <td>
              <button @click="generarRecibo(retirada)">Generar Recibo</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { generarRecibo } from '@/utils/pdfutils';
  import apiClient from '@/services/api';
  
  export default {
    data() {
      return {
        retiradas: [],
      };
    },
    async created() {
      const response = await apiClient.get('/retiradas');
      this.retiradas = response.data;
    },
    methods: {
      generarRecibo(retirada) {
        generarRecibo(retirada);
      },
    },
  };
  </script>