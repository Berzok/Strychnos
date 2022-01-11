/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
//import './bootstrap';

import App from './src/App.vue';
import { createApp } from 'vue';
import configureHTTPInterceptor from './src/config/configureHTTPInterceptor';
import i18n from './src/utils/i18n';
import router from './src/router';


import Toast from 'vue-toastification';
import VueAxios from 'vue-axios';
import axios from 'axios';
import { createPinia } from 'pinia'

//Bootstrap and bootswatch
import 'bootswatch/dist/darkly/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min';

import '@fortawesome/fontawesome-free/css/all.min.css';

// Import the notification CSS or use your own!
import 'vue-toastification/dist/index.css';

//All from primevue
import PrimeVue from 'primevue/config';
import 'primeflex/primeflex.css';
import 'primevue/resources/primevue.css';
import 'primeicons/primeicons.css';
import 'primevue/resources/themes/md-dark-deeppurple/theme.css';


//Custom css
import './styles/app.css';
import './styles/tags.css';
import './styles/bootstrap_override.css';


const toastOptions = {
    // You can set your default options here
    // see : https://www.npmjs.com/package/vue3-toast-single
};
const pinia = createPinia();

configureHTTPInterceptor();

createApp(App)
    .use(Toast, toastOptions)
    .use(VueAxios, axios)
    .use(i18n)
    .use(router)
    .use(PrimeVue)
    .use(pinia)
    .mount('#app');
