import './bootstrap';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from "vue/dist/vue.esm-bundler.js";
import { createRouter, createWebHistory } from "vue-router";
import Routes from "./routes";
import Store from "./store"
const app = createApp({})
const routers = createRouter({
    history: createWebHistory(),
    routes: Routes
})
app.use(Store)
app.use(routers)
app.mount("#app")
