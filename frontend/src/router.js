import { createRouter, createWebHistory } from 'vue-router';
import HelloWorld from './components/HelloWorld.vue';
import Home from './components/HomeComponent.vue';
import Add from './components/AddNewspaperComponent.vue'
import Get from './components/GetComponent.vue'

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
    {
        path: '/add',
        name: 'Add',
        component: Add
    },
    {
        path: '/get',
        name: 'Get',
        component: Get
    }
    // Add more routes as needed
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;