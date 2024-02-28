<template>
    <div>
        <v-toolbar dark prominent>
            <v-toolbar-title>VueScraper</v-toolbar-title>

            <v-spacer></v-spacer>
            <router-link to="/home">
                <v-btn>Home</v-btn>
            </router-link>
            <router-link to="/add">
                <v-btn>Add</v-btn>
            </router-link>
            <v-btn @click="submitForm">Cerrar sesión</v-btn>
            <!-- <router-link to="/get">
                <v-btn>Get</v-btn>
            </router-link> -->
        </v-toolbar>
    </div>
</template>

<script setup>
import { getTokenFromCookie, deleteTokenCookie } from './cookieUtils';

const submitForm = () => {
    fetch('http://localhost:8000/api/logout', {
        headers: {
            'Authorization': `Bearer ${getTokenFromCookie()}` // Add the token to the Authorization header
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data)

            deleteTokenCookie();

            setTimeout(() => {
               window.location.href = '/' 
            }, 1000);
        })
        .catch(error => {
            console.error('Error: ', error)
            isLoading.value = false
        })
}
</script>