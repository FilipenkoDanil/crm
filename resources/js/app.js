import './bootstrap';
import { createApp } from 'vue';
import App from "./App.vue";
import vuetify from "./vuetify";
import router from "./router";
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'



createApp(App).use(vuetify).use(router).use(LaravelPermissionToVueJS).mount('#app');
