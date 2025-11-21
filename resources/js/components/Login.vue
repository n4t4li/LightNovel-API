<template>
    <div class="login container">
        <h1>Login</h1>

        <form @submit.prevent="submit">
            <div>
                <label for="email">Email</label><br />
                <input id="email" v-model="form.email" type="email" required />
            </div>

            <div style="margin-top: 0.5rem">
                <label for="password">Password</label><br />
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                />
            </div>

            <div style="margin-top: 0.75rem">
                <button :disabled="loading">
                    {{ loading ? "Logging in..." : "Login" }}
                </button>
            </div>

            <div v-if="error" style="color: #b00020; margin-top: 0.5rem">
                {{ error }}
            </div>
        </form>

        <p style="margin-top: 1rem">
            Test accounts: create one with your Laravel backend or use tinker.
        </p>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Login",
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            loading: false,
            error: null,
        };
    },
    methods: {
        async submit() {
            this.error = null;
            this.loading = true;
            try {
                // Default Laravel auth expects POST /login (adjust if using sanctum/api)
                const response = await axios.post("/login", this.form);

                // On success Laravel typically redirects — backend should return session cookie
                // Optionally backend can return JSON user; try to refresh auth payload:
                await this.refreshAuth();

                // redirect to dashboard or home
                this.$router.push({ name: "dashboard" });
            } catch (err) {
                // Try to display a meaningful message
                if (
                    err.response &&
                    err.response.data &&
                    err.response.data.message
                ) {
                    this.error = err.response.data.message;
                } else {
                    this.error =
                        "Login failed. Check credentials and try again.";
                }
            } finally {
                this.loading = false;
            }
        },

        async refreshAuth() {
            // Fetch a small endpoint that returns the authenticated user as JSON
            // Create this route on server if missing (e.g. GET /api/user or /auth/user)
            try {
                const res = await axios.get("/user"); // adjust path if needed
                const auth = { isLoggedin: !!res.data, user: res.data || null };
                window.__USER_AUTH__ = auth;
            } catch (e) {
                // If endpoint doesn't exist, try a page refresh to get Blade-variable updated
                window.location.reload();
            }
        },
    },
};
</script>

<style scoped>
.container {
    padding: 1rem;
    max-width: 420px;
    margin: 0 auto;
}
input {
    width: 100%;
    padding: 0.4rem;
    box-sizing: border-box;
}
button {
    padding: 0.5rem 1rem;
}
</style>
