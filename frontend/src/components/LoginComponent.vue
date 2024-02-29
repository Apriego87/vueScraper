<template>
    <div id="cont" class="card m-3">
        <div id="cont" class="card m-3">
            <div class="card-body d-flex flex-column align-center justify-center">
                <div class="w-100 text-center mt-3">
                    <h1>Inicio de Sesión</h1>
                    <v-form ref="form" class="mx-2" lazy-validation>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field v-model="email" :rules="emailRules" label="E-Mail">
                                </v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field type="password" v-model="password" :rules="passwordRules" label="Contraseña">
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <div class="w-100 d-flex flex-row align-center justify-space-evenly">
                            <v-btn class="purple darken-2 white--text mt-5" @click="submitForm">Enviar</v-btn>
                            <!-- <router-link to="/register">
                                <v-btn class="purple darken-2 white--text mt-5">Registrarse</v-btn>
                            </router-link> -->
                        </div>
                    </v-form>
                </div>
                <div>
                    <p>Todavía no estás registrado? Empieza por <a href="/register">aquí</a></p>
                </div>
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
import { ref, onMounted } from 'vue';
import { getTokenFromCookie } from './cookieUtils';

const email = ref('');
const password = ref('');
const isAuthenticated = ref(false);

const emailRules = [
    v => !!v || 'Mail is required',
];

const passwordRules = [
    v => !!v || 'Password is required',
];

onMounted(() => {
    const token = getTokenFromCookie();
    isAuthenticated.value = !!token;

    // If token is set, redirect to the home page
    if (isAuthenticated.value) {
        window.location.href = '/home';
    }
});

const submitForm = () => {
    fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            email: email.value,
            password: password.value
        }),
    })
        .then(response => {
            if (response.ok) {
                console.log('all good')
                return response.json(); // Return the response data
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .then(data => {
            // Extract the token from the response data
            const token = data.token;

            // Set the token as a cookie
            setCookie('token', token, 1); // Change '1' to the number of days you want the cookie to last

            // Fetch user data after login
            fetch('http://localhost:8000/api/profile', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(userData => {
                    // Store user data in sessionStorage
                    sessionStorage.setItem('userData', JSON.stringify(userData.data));
                    // Redirect to '/home' or do any other necessary actions
                    setTimeout(() => {
                        window.location.href = '/home';
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                });
        })
        .catch(error => {
            // Handle any errors during login
            console.error('There was a problem with the login operation:', error);
        });
};


const setCookie = (name, value, days) => {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = 'expires=' + date.toUTCString();
    document.cookie = name + '=' + value + ';' + expires + ';path=/';
};
</script>