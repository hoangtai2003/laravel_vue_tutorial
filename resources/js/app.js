import './bootstrap';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';

import { createApp } from "vue/dist/vue.esm-bundler.js";
import { createRouter, createWebHistory } from "vue-router";
import Routes from "./routes";
import Login from "@/pages/auth/Login.vue";
import Register from "@/pages/auth/Register.vue";
const app = createApp({})
const routers = createRouter({
    history: createWebHistory(),
    routes: Routes
})
app.use(routers)
app.component('Login', Login);
app.component('Register', Register);
app.mount("#app")
