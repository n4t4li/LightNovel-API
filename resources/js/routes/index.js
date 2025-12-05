import { createRouter, createWebHistory } from "vue-router";

import Home from "../components/Home.vue";
import Dashboard from "../components/Dashboard.vue";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";
import APropos from "../components/APropos.vue";
import IndexLightNovels from "../components/light_novels/Index.vue";
import CreateLightNovel from "../components/light_novels/Create.vue";
import ShowLightNovel from "../components/light_novels/Show.vue";
import EditLightNovel from "../components/light_novels/Edit.vue";

const routes = [
    { path: "/", component: Home, name: "home" },
    { path: "/login", component: Login, name: "login" },
    { path: "/dashboard", component: Dashboard, name: "dashboard" },
    { path: "/register", component: Register, name: "register" },
    { path: "/apropos", component: APropos, name: "apropos" },
    {
        path: "/lightnovels",
        component: IndexLightNovels,
        name: "lightnovels",
    },
    {
        path: "/lightnovels/create",
        component: CreateLightNovel,
        name: "lightnovels_create",
    },
    {
        path: "/lightnovels/:id",
        component: ShowLightNovel,
        name: "lightnovels_show",
    },
    {
        path: "/lightnovels/:id/edit",
        component: EditLightNovel,
        name: "lightnovels_edit",
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
