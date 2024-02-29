<template>
  <div id="cont" class="card m-3">
    <div id="toolbar">
      <toolbar-component />
    </div>
    <div class="card-body d-flex flex-column align-center justify-center">
      <div class="w-100 text-center mt-3">
        <h1>Añadir periódico</h1>
        <v-form ref="form" class="mx-2" lazy-validation>
          <v-row>
            <v-col cols="6">
              <!-- Select input for newspaper names -->
              <v-select v-model="selectedName" :items="newspaperNames" label="Seleccionar periódico"
                @change="selectNewspaper"></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="6">
              <!-- Text field for the name, bound to selectedName -->
              <v-text-field v-model="selectedName" :rules="nameRules" label="Nombre"></v-text-field>
            </v-col>
            <v-col cols="6">
              <!-- Text field for the link -->
              <v-text-field v-model="link" :rules="linkRules" label="Link"></v-text-field>
            </v-col>
          </v-row>
          <v-btn class="purple darken-2 white--text mt-5" @click="submitForm"> Enviar</v-btn>
        </v-form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getTokenFromCookie } from './cookieUtils';
import ToolbarComponent from './ToolbarComponent.vue';

const name = ref('');
const link = ref('');
const newspaperNames = ref([]); // Array to store newspaper names
const selectedName = ref(''); // Selected newspaper name

const nameRules = [
  v => !!v || 'Name is required',
];

const linkRules = [
  v => !!v || 'LINK is required',
];

onMounted(() => {
  fetchNewspaperNames(); // Fetch newspaper names when the component is mounted
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
      newspaperNames.value = data.list; // Update the newspaperNames array with the fetched data
    })
    .catch(error => {
      console.error('Error fetching newspaper names:', error);
    });
};

const selectNewspaper = () => {
  // Update the name field with the selected newspaper name
  name.value = selectedName.value;
};

const submitForm = () => {
  const userDataString = sessionStorage.getItem('userData');
  const userData = JSON.parse(userDataString);
  console.log(userData)
  const userID = userData.id;

  fetch('http://localhost:8000/api/newspapers', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${getTokenFromCookie()}`
    },
    body: JSON.stringify({
      name: name.value, // Use the selected newspaper name
      link: link.value,
      userID: userID
    }),
  })
    .then(response => {
      if (response.ok) {
        alert('periódico añadido correctamente')
        setTimeout(() => {
          window.location.href = '/home'
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
