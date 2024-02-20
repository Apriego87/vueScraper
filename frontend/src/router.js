import { createRouter, createWebHistory } from 'vue-router';
import HelloWorld from './components/HelloWorld.vue';
import Home from './components/HomeComponent.vue';

const routes = [
    {
        path: '/',
        name: 'HelloWorld',
        component: HelloWorld
    },
    {
        path: '/home',
        name: 'Home',
        component: Home
    },
    // Add more routes as needed
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;