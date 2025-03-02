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
        { path: '/login', component: () => import('./components/Login.vue') },
        { 
            path: '/home', 
            component: () => import('./components/HomeView.vue'),
            meta: { requiresAuth: true }
        },
        { 
            path: '/vehiculos', 
            component: () => import('./components/VehiculosView.vue'),
            meta: { requiresAuth: true }
        },
        { 
            path: '/retiradas', 
            component: () => import('./components/Retiradas.vue'),
            meta: { requiresAuth: true } 
        },
        { 
            path: '/nueva-retirada', 
            component: () => import('./components/NuevaRetirada.vue'),
            meta: { requiresAuth: true } 
        },
        { 
            path: '/users', 
            component: () => import('./components/Users.vue'),
            meta: { requiresAuth: true } 
        },
        { 
            path: '/', 
            redirect: '/home' 
        }
    ]
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    
    if (requiresAuth && !token) {
        next('/login');
    } else if (to.path === '/login' && token) {
        next('/home');
    } else {
        next();
    }
});

// Crear la aplicación Vue
const app = createApp(App);

// Registrar plugins
app.use(router);
app.use(PrimeVue);

// Montar la aplicación
app.mount('#app');