import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';

// PrimeVue imports
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/saga-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';

// Configuración del router
const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/login' },
        { path: '/login', component: () => import('./components/Login.vue') },
        { path: '/vehiculos', component: () => import('./components/Vehiculos.vue') },
        { path: '/retiradas', component: () => import('./components/Retiradas.vue') },
        { path: '/nueva-retirada', component: () => import('./components/NuevaRetirada.vue') },
        { path: '/users', component: () => import('./components/Users.vue') }
    ]
});

// Crear la aplicación Vue
const app = createApp(App);

// Registrar plugins
app.use(router);
app.use(PrimeVue);

// Montar la aplicación
app.mount('#app');