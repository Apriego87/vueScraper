<template>
    <div class="card m-3">
        <div class="card-body">
            <div v-if="isLoading">
                <h1 class="text-center my-3"><u>Cargando...</u></h1>
            </div>
            <div v-else-if="products">
                <h1 class="text-center my-3"><u>Todas las Noticias</u></h1>
                <div class="d-flex flex-row flex-wrap items-center justify-center">
                    <div class="d-flex flex-row items-center justify-center mx-3" v-for="product in products"
                        :key="product.id">
                        <v-card class="my-3" width="20vw" min-width="300" :href="product.link">
                            <v-card-item class="text-center">
                                <v-card-title class="text-wrap">{{ product.title }}</v-card-title>
                            </v-card-item>
                        </v-card>
                    </div>
                </div>
            </div>
            <div v-else class="text-center">
                <div class="spinner-border spinner-border-sm"></div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref } from 'vue';
const products = ref(null);
const isLoading = ref(true); // State variable to track loading state

fetch('http://localhost:8000/api/newspapers')
    .then(response => response.json())
    .then(data => {
        products.value = data.items;
        isLoading.value = false; // Set loading state to false when data is fetched
        console.log(data); // Log the fetched data to the console
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        isLoading.value = false; // Set loading state to false in case of error
    });
</script>
  