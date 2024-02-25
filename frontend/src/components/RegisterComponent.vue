<template>
    <div id="cont" class="card m-3">
        <div class="card-body d-flex flex-column align-center justify-center">
            <div class="w-100 text-center mt-3">
                <h1>Registro</h1>
                <v-form ref="form" class="mx-2" lazy-validation>
                    <v-row class="d-flex flex-column align-center">
                        <v-col cols="9">
                            <v-text-field v-model="name" label="Nombre">
                            </v-text-field>
                        </v-col>
                        <v-col cols="9">
                            <v-text-field type="email" v-model="email" :rules="emailRules" label="E-Mail">
                            </v-text-field>
                        </v-col>
                        <v-col cols="9">
                            <v-text-field type="password" v-model="password" :rules="passwordRules" label="Contraseña">
                            </v-text-field>
                        </v-col>
                        <v-col cols="9">
                            <v-text-field type="password" v-model="passwordConf" :rules="passwordRules"
                                label="Confirmar contraseña">
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <div class="w-100 d-flex flex-row align-center justify-space-evenly">
                        <v-btn class="purple darken-2 white--text mt-5" @click="submitForm"> Enviar</v-btn>
                        <router-link to="/login">
                            <v-btn class="purple darken-2 white--text mt-5">Iniciar sesión</v-btn>
                        </router-link>
                    </div>

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

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConf = ref('');

const emailRules = [
    v => !!v || 'Mail is required',
];

const passwordRules = [
    v => !!v || 'Password is required',
];

const submitForm = () => {
    fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: passwordConf.value
        }),
    })
        .then(response => {
            if (response.ok) {
                // window.location.href = '/'
                console.log('all good')
                console.log(response)
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