<template>
    <div class="dashboard container">
        <h1>Dashboard</h1>

        <div v-if="auth && auth.isLoggedin">
            <p><strong>Name:</strong> {{ auth.user.name ?? "—" }}</p>
            <p><strong>Email:</strong> {{ auth.user.email ?? "—" }}</p>

            <div style="margin-top: 1rem">
                <button @click="logout" :disabled="loading">
                    {{ loading ? "Logging out..." : "Logout" }}
                </button>
            </div>
        </div>

        <div v-else>
            <p>
                You are not logged in.
                <router-link to="/login">Login</router-link>
            </p>
        </div>

        <div v-if="error" style="color: #b00020; margin-top: 0.5rem">
            {{ error }}
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Dashboard",
    data() {
        return {
            auth: window.__USER_AUTH__ || null,
            loading: false,
            error: null,
        };
    },
    methods: {
        async logout() {
            this.loading = true;
            this.error = null;
            try {
                // Standard Laravel logout endpoint
                await axios.post("/logout");
                // Clear frontend auth and redirect to home
                window.__USER_AUTH__ = { isLoggedin: false, user: null };
                this.auth = window.__USER_AUTH__;
                this.$router.push({ name: "home" });
            } catch (err) {
                this.error = "Logout failed.";
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.container {
    padding: 1rem;
    max-width: 800px;
    margin: 0 auto;
}
button {
    padding: 0.5rem 1rem;
}
</style>
