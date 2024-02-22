<template>
    <div id="cont" class="card m-3">
        <div class="card-body d-flex flex-column align-center justify-center">
            <div class="w-100 text-center mt-3">
                <h1>Buscar por nombre: </h1>
                <div id="cont">
                    <v-select class="w-50" label="Elige un periódico" :items="newspaperNames"></v-select>
                </div>
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
import { ref, onMounted } from 'vue';

const newspaperNames = ref([]);

onMounted(async () => {
    try {
        const response = await fetch('http://localhost:8000/api/getNames');
        const data = await response.json();
        newspaperNames.value = data.list;
        console.log(data)
    } catch (error) {
        console.error('Error fetching newspaper names:', error);
    }
});
</script>
