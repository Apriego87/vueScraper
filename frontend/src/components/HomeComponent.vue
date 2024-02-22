<template>
    <div class="card m-3">
        <div class="card-body">
            <div v-if="products">
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
const products = ref(null)

fetch('http://localhost:8000/api/index')
    .then(response => response.json())
    .then(data => {
        products.value = data.items;
        console.log(data); // Log the fetched data to the console
    });
</script>
  