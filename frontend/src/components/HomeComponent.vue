<template>
    <div class="card m-3">
        <toolbar-component />
        <div class="card-body">
            <div v-if="isLoading">
                <h1 class="text-center my-3"><u>Cargando...</u></h1>
            </div>
            <div v-else-if="products">
                <div id="cont" class="card m-3">
                    <div class="card-body d-flex flex-column align-center justify-center">
                        <div class="w-100 text-center mt-3">
                            <h1>Buscar por nombre: </h1>
                            <div id="cont">
                                <v-select v-model="selected" @update:modelValue="filter(selected)" class="w-50"
                                    label="Elige un periódico"
                                    :items="[...newspaperNames, 'Todos los periódicos']"></v-select>
                            </div>
                        </div>
                    </div>
                </div>
                <h1 class="text-center my-3"><u>Todas las Noticias</u></h1>
                <div class="d-flex flex-row flex-wrap items-center justify-center">
                    <div class="d-flex flex-row items-center justify-center mx-3" v-for="product in filteredProducts"
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

<style scoped>
#cont {
    width: 100vw;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
}
</style>
  
<script setup>
import { ref, computed } from 'vue'
import { getTokenFromCookie } from './cookieUtils';
import ToolbarComponent from './ToolbarComponent.vue';

const products = ref(null)
const isLoading = ref(true)
const selected = ref(null)
const newspaperNames = ref([])

// Fetch data from the API and include the token in the request headers
fetch('http://localhost:8000/api/newspapers', {
    headers: {
        'Authorization': `Bearer ${getTokenFromCookie()}`
    }
})
    .then(response => response.json())
    .then(data => {
        products.value = data.items
        isLoading.value = false
        newspaperNames.value = Array.from(new Set(data.items.map(item => item.name)))
    })
    .catch(error => {
        console.error('Error fetching data:', error)
        isLoading.value = false
    })

function filter(name) {
    if (name === 'Todos los periódicos') {
        selected.value = null
    } else {
        selected.value = name
    }
}

const filteredProducts = computed(() => {
    if (!selected.value || !products.value) return products.value
    return selected.value ? products.value.filter(product => product.name === selected.value) : products.value
})
</script>
