import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/HomeComponent.vue';
import Add from './components/AddNewspaperComponent.vue'
import Login from './components/LoginComponent.vue'
import Register from './components/RegisterComponent.vue'
// import HelloWorld from './components/HelloWorld.vue';
// import Get from './components/GetComponent.vue'

const routes = [
    {
        path: '/',
        name: '/',
        component: Login
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
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    /* {
        path: '/get',
        name: 'Get',
        component: Get
    } */
    // Add more routes as needed
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;