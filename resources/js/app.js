import './bootstrap';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import VueCookie from 'vue-cookie';
import { createApp } from "vue/dist/vue.esm-bundler.js";
import { createRouter, createWebHistory } from "vue-router";
import Routes from "./routes";
const app = createApp({})
const routers = createRouter({
    history: createWebHistory(),
    routes: Routes
})
app.use(VueCookie);
app.provide('cookies', app.config.globalProperties.$cookies);
routers.beforeEach((to, from, next) => {
    const isAuthenticated = VueCookie.get('access_token');

    if (to.name !== 'admin.login' && !isAuthenticated) {
        next({ name: 'admin.login' });
    } else if (to.name === 'admin.login' && isAuthenticated) {
        next({ name: 'admin.dashboard' });
    } else {
        next();
    }
});
app.use(routers)
app.mount("#app")
