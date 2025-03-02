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
            path: '/logs', 
            component: () => import('./components/Logs.vue'),
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
    const userRole = localStorage.getItem('userRole');

    // Redirect authenticated users away from login page
    if (to.path === '/login' && token) {
        next('/home');
        return;
    }

    // Redirect unauthenticated users to login
    if (to.meta.requiresAuth && !token) {
        next('/login');
        return;
    }

    // Check for admin-only routes
    if (to.path === '/users' && (!userRole || userRole !== 'admin')) {
        next('/home');
        return;
    }

    next();
});

const app = createApp(App);
app.use(router);
app.use(PrimeVue);
app.mount('#app');