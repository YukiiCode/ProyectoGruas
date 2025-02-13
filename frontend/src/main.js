import './assets/main.css'; // Importa tus estilos globales
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Importaciones de PrimeVue
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/saga-blue/theme.css'; // Tema de PrimeVue
import 'primevue/resources/primevue.min.css'; // Estilos base de PrimeVue
import 'primeicons/primeicons.css'; // Iconos de PrimeVue

const app = createApp(App);

// Usa el router
app.use(router);

// Registra PrimeVue
app.use(PrimeVue);

// Monta la aplicación
app.mount('#app');