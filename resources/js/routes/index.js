import { createRouter, createWebHistory } from "vue-router";

import Home from "../components/Home.vue";
import Dashboard from "../components/Dashboard.vue";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";

const routes = [
    { path: "/", component: Home, name: "home" },
    { path: "/login", component: Login, name: "login" },
    { path: "/dashboard", component: Dashboard, name: "dashboard" },
    { path: "/register", component: Register, name: "register" },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
