<template>
  <div class="card m-3">
    <div class="card-body d-flex flex-column align-center justify-center">
      <div class="w-50 text-center mt-3">
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
          <v-btn class="purple darken-2 white--text mt-5" @click="submitForm"> Register </v-btn>
        </v-form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const name = ref('');
const link = ref('');

const nameRules = [
  v => !!v || 'Name is required',
];

const linkRules = [
  v => !!v || 'LINK is required',
];

const submitForm = () => {
  console.log(name.value)
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  console.log(csrfToken)

  fetch('http://localhost:8000/api/add', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      name: name.value,
      link: link.value
    }),
  })
    .then(response => {

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