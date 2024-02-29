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
              <v-text-field v-model="name" :rules="nameRules" label="Nombre">
              </v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field v-model="link" :rules="linkRules" label="Link">
              </v-text-field>
            </v-col>
          </v-row>
          <v-btn class="purple darken-2 white--text mt-5" @click="submitForm"> Enviar</v-btn>
        </v-form>
      </div>
    </div>
  </div>
</template>

<style scoped>
* {
  margin: 0;
  box-sizing: border-box;
}

#toolbar {
  position: absolute;
  top: 0;
  width: 100%;
}

#cont {
  height: calc(100vh - 64px);
  width: 100vw;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
}

.card-body {
  background-color: white;
  width: 50vw;
  min-width: 600px;
  padding: 20px;
  border-radius: 10px;
}

.card-body>* {
  margin: 20px;
}
</style>

<script setup>
import { ref } from 'vue';
import { getTokenFromCookie } from './cookieUtils';
import ToolbarComponent from './ToolbarComponent.vue';

const name = ref('');
const link = ref('');

const nameRules = [
  v => !!v || 'Name is required',
];

const linkRules = [
  v => !!v || 'LINK is required',
];

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
      name: name.value,
      link: link.value,
      userID: userID // Use the parsed user ID
    }),
  })
    .then(response => {
      if (response.ok) {
        alert('periódico añadido correctamente')
        /* setTimeout(() => {
          window.location.href = '/home'
        }, 1000); */
      }
      return response.json();
    })
    .then(data => {
      // Handle the response data
      console.log(data);
    })
    .catch(error => {
      // Handle any errors
      console.error('There was a problem with the fetch operation:', error);
    });
};
</script>
