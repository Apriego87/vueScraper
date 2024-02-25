// main.js

// Import Vue and createApp function from Vue
import { createApp } from 'vue'

// Import App.vue component
import App from './App.vue'

// Import Vue Router instance
import router from './router'

// Import Vuetify
import { createVuetify } from 'vuetify'

// Import Vuetify CSS
import 'vuetify/dist/vuetify.min.css'

// Create a new Vue app instance
const app = createApp(App)

// Register Vue Router instance
app.use(router)

// Create Vuetify instance
const vuetify = createVuetify()

// Use Vuetify in the app
app.use(vuetify)

// Mount the app to the DOM element with id 'app'
app.mount('#app')
