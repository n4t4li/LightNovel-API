<template>
    <div class="container">
        <h1>Welcome</h1>

        <div v-if="auth && auth.isLoggedin" class="welcome-message">
            <p>
                Hello,
                <strong>{{
                    auth.user.name ?? auth.user.email ?? "User"
                }}</strong>
                — you're logged in.
            </p>
            <router-link to="/dashboard" class="btn-primary"
                >Go to Dashboard</router-link
            >
        </div>
        <div v-else class="welcome-message">
            <p>You are not logged in.</p>
            <router-link to="/login" class="btn-primary">Login</router-link>
        </div>

        <section class="info-section">
            <h2>About This Application</h2>
            <p>
                This is a Single Page Application (SPA) built with Laravel and
                Vue.js.
            </p>
            <p>
                The application uses Vue Router for client-side routing,
                providing a seamless user experience without full page reloads.
            </p>
        </section>
    </div>
</template>

<script>
export default {
    name: "Home",
    computed: {
        auth() {
            // Get auth state from window (set by Blade template or updated after login)
            if (window.__USER_AUTH__) {
                if (typeof window.__USER_AUTH__ === "string") {
                    try {
                        return JSON.parse(atob(window.__USER_AUTH__));
                    } catch (e) {
                        return { isLoggedin: false, user: null };
                    }
                }
                return window.__USER_AUTH__;
            }
            return { isLoggedin: false, user: null };
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
}

.welcome-message {
    padding: 1.5rem;
    background-color: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 2rem;
    text-align: center;
}

.welcome-message p {
    margin-bottom: 1rem;
    font-size: 1.1rem;
    color: #555;
}

.btn-primary {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.info-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e0e0e0;
}

.info-section h2 {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.info-section p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}
</style>
