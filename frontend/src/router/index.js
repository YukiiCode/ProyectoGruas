import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Login.vue';
import VehiculosList from '@/views/VehiculosList.vue';
import RetiradasList from '@/views/RetiradasList.vue';

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/vehiculos', component: VehiculosList },
  { path: '/retiradas', component: RetiradasList },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;