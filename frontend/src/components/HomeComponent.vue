<script setup>
import { ref } from 'vue';
const products = ref(null)

fetch('http://localhost:8000/index')
    .then(response => response.json())
    .then(data => {
        products.value = data;
        console.log(data); // Log the fetched data to the console
    });
</script>

<template>
    <div class="card m-3">
        <div class="card-body">
            <div v-if="products">
                <h2 class="text-center my-3">Noticias</h2>
                <!-- <ul class="mb-0">
                    <a v-for="product in products" :key="product.id" :href="product.link">{{ product.title }}</a>
                </ul> -->
                <div class="d-flex flex-column align-center justify-center">
                    <div class="w-50 my-2" v-for="product in products" :key="product.id">
                        <a :href="product.link">{{ product.title }}</a>
                    </div>
                </div>
            </div>
            <div v-if="!products" class="text-center">
                <div class="spinner-border spinner-border-sm"></div>
            </div>
        </div>
    </div>
</template>