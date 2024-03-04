<template>
  <div id="cont" class="card m-3">
    <div id="toolbar">
      <toolbar-component />
    </div>
    <div id="cont" class="card-body d-flex flex-column align-center justify-center">
      <div v-if="isLoading" class="w-100 text-center mt-3">
        <h1>Añadir periódico</h1>
        <v-form ref="form" class="mx-2 mt-5" lazy-validation>
          <v-row class="d-flex flex-row align-center justify-center">
            <v-col cols="3">
              <v-select v-model="selectedName" :items="newspaperNames" label="Seleccionar periódico"
                @change="selectNewspaper"></v-select>
            </v-col>
          </v-row>
          <v-row class="d-flex flex-row align-center justify-center">
            <v-col cols="4">
              <v-text-field v-model="selectedName" :rules="nameRules" label="Nombre"></v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field v-model="link" :rules="linkRules" label="Link"></v-text-field>
            </v-col>
          </v-row>
          <v-btn class="purple darken-2 white--text mt-5" @click="submitForm"> Enviar</v-btn>
        </v-form>
      </div>
      <div v-if="!isLoading">
        <loading-component></loading-component>
      </div>
    </div>
  </div>
</template>

<style scoped>
#cont {
  height: calc(100vh - 64px);
}
</style>

<script setup>
import { ref, onMounted } from 'vue';
import { getTokenFromCookie } from './cookieUtils';
import ToolbarComponent from './ToolbarComponent.vue';
import LoadingComponent from './LoadingComponent.vue';

const name = ref('');
const link = ref('');
const newspaperNames = ref([]);
const selectedName = ref('');
const isLoading = ref(false)

const nameRules = [
  v => !!v || 'El nombre es necesario',
];

const linkRules = [
  v => !!v || 'El link es necesario',
];

onMounted(() => {
  fetchNewspaperNames();
});

const fetchNewspaperNames = () => {
  fetch('http://localhost:8000/api/getNames', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${getTokenFromCookie()}`
    }
  })
    .then(response => response.json())
    .then(data => {
      newspaperNames.value = data.list;
      isLoading.value = true
    })
    .catch(error => {
      console.error('Error fetching newspaper names:', error);
    });
};

const selectNewspaper = () => {
  name.value = selectedName.value;
};

const submitForm = () => {
  const userDataString = sessionStorage.getItem('userData');
  const userData = JSON.parse(userDataString);
  const userID = userData.id;

  fetch('http://localhost:8000/api/newspapers', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${getTokenFromCookie()}`
    },
    body: JSON.stringify({
      name: selectedName.value,
      link: link.value,
      userID: userID
    }),
  })
    .then(response => {
      if (response.ok) {
        alert('periódico añadido correctamente');
        setTimeout(() => {
          window.location.href = '/home';
        }, 1000);
      }
      return response.json();
    })
    .then(data => {
      console.log(data);
    })
    .catch(error => {
      console.error('There was a problem with the fetch operation:', error);
    });
};
</script>
