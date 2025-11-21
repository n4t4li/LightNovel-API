<template>
    <div class="container">
        <h1>Welcome</h1>

        <p v-if="auth && auth.isLoggedin">
            Hello,
            <strong>{{ auth.user.name ?? auth.user.email ?? "User" }}</strong> —
            you're logged in.
        </p>
        <p v-else>You are not logged in.</p>

        <nav>
            <router-link to="/">Home</router-link> |
            <router-link v-if="!auth || !auth.isLoggedin" to="/login"
                >Login</router-link
            >
            <router-link v-if="auth && auth.isLoggedin" to="/dashboard"
                >Dashboard</router-link
            >
        </nav>

        <section style="margin-top: 1rem">
            <h2>App Info</h2>
            <p>Simple SPA shell using Laravel + Vue.</p>
        </section>
    </div>
</template>

<script>
export default {
    name: "Home",
    data() {
        return {
            auth: window.__USER_AUTH__ || null,
        };
    },
};
</script>

<style scoped>
.container {
    padding: 1rem;
    max-width: 800px;
    margin: 0 auto;
}
nav a {
    margin-right: 0.5rem;
}
</style>
