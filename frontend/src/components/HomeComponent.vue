<template>
    <div class="card m-3">
        <toolbar-component />
        <div class="card-body">
            <div v-if="isLoading">
                <div id="loader" class="d-flex flex-column align-center justify-center">
                    <loading-component></loading-component>
                </div>
            </div>
            <div v-else-if="newspapers">
                <div id="cont" class="card m-3">
                    <div class="card-body d-flex flex-column align-center justify-center">
                        <div class="w-100 text-center mt-3">
                            <h1>Filtrar por nombre: </h1>
                            <div id="cont">
                                <v-select v-model="selected" @update:modelValue="filter(selected)" class="w-50"
                                    label="Elige un periódico"
                                    :items="['Todos mis periódicos', ...newspaperNames]"></v-select>
                            </div>
                        </div>
                    </div>
                </div>
                <h1 class="text-center my-3"><u>Todas las Noticias</u></h1>
                <div class="d-flex flex-row flex-wrap items-center justify-center">
                    <div class="d-flex flex-row items-center justify-center mx-3" v-for="product in filteredProducts"
                        :key="product.id">
                        <v-card class="my-3 d-flex flex-column align-center justify-center" width="20vw" min-width="300"
                            :href="product.link">
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

#loader {
    height: calc(100vh - 64px);
}
</style>
  
<script setup>
import { ref, computed } from 'vue'
import { getTokenFromCookie } from './cookieUtils';
import ToolbarComponent from './ToolbarComponent.vue';
import LoadingComponent from './LoadingComponent.vue';

const newspapers = ref(null)
const isLoading = ref(true)
const selected = ref(null)
const newspaperNames = ref([])
var userNewspapers = ref([])
const userID = JSON.parse(sessionStorage.getItem('userData')).id;

fetch('http://localhost:8000/api/userNewspapers', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${getTokenFromCookie()}`
    },
    body: JSON.stringify({
        id: userID
    }),
})
    .then(response => response.json())
    .then(data => {
        userNewspapers = data.nps
        // Fetch data from the API and include the token in the request headers
        fetch('http://localhost:8000/api/newspapers', {
            headers: {
                'Authorization': `Bearer ${getTokenFromCookie()}`
            }
        })
            .then(response => response.json())
            .then(data => {
                newspapers.value = data.items.filter(item => userNewspapers.includes(item.npId));
                isLoading.value = false
                newspaperNames.value = Array.from(new Set(data.items
                    .filter(item => userNewspapers.includes(item.npId))
                    .map(item => item.name)));
            })
            .catch(error => {
                console.error('Error fetching data:', error)
                isLoading.value = false
            })
    })
    .catch(error => {
        console.error('Error fetching data:', error)
        isLoading.value = false
    })

function filter(name) {
    if (name === 'Todos mis periódicos') {
        selected.value = null
    } else {
        selected.value = name
    }
}

const filteredProducts = computed(() => {
    if (!selected.value || !newspapers.value) return newspapers.value
    return selected.value ? newspapers.value.filter(product => product.name === selected.value) : newspapers.value
})
</script>
