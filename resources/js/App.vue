<template>
    <div id="app">
        <!-- Navigation Bar - Always visible -->
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-brand">
                    <router-link to="/" class="nav-logo">
                        {{ appName }}
                    </router-link>
                </div>
                <div class="nav-links">
                    <router-link to="/" class="nav-link">Home</router-link>
                    <template v-if="auth && auth.isLoggedin">
                        <router-link to="/dashboard" class="nav-link"
                            >Dashboard</router-link
                        >
                        <span class="nav-user">
                            {{ auth.user?.name || auth.user?.email || "User" }}
                        </span>
                        <button
                            @click="handleLogout"
                            class="nav-button"
                            :disabled="loggingOut"
                        >
                            {{ loggingOut ? "Logging out..." : "Logout" }}
                        </button>
                    </template>
                    <template v-else>
                        <router-link to="/login" class="nav-link"
                            >Login</router-link
                        >
                    </template>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Router view will render the matched component based on the current route -->
            <router-view :key="$route.fullPath"></router-view>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <p>
                    &copy; {{ currentYear }} {{ appName }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "App",
    data() {
        return {
            appName: "LightNovel SPA",
            loggingOut: false,
        };
    },
    computed: {
        auth() {
            // Get auth state from window (set by Blade template or updated after login)
            if (window.__USER_AUTH__) {
                // If it's a string (Base64), decode it
                if (typeof window.__USER_AUTH__ === "string") {
                    try {
                        return JSON.parse(atob(window.__USER_AUTH__));
                    } catch (e) {
                        console.warn("Failed to decode auth data:", e);
                        return { isLoggedin: false, user: null };
                    }
                }
                // If it's already an object, return it
                return window.__USER_AUTH__;
            }
            return { isLoggedin: false, user: null };
        },
        currentYear() {
            return new Date().getFullYear();
        },
    },
    mounted() {
        // Decode the auth data from Base64 if available (on initial load)
        if (window.__USER_AUTH__ && typeof window.__USER_AUTH__ === "string") {
            try {
                window.__USER_AUTH__ = JSON.parse(atob(window.__USER_AUTH__));
            } catch (e) {
                console.warn("Failed to decode auth data:", e);
                window.__USER_AUTH__ = { isLoggedin: false, user: null };
            }
        }

        // Remove loading indicator once Vue has mounted
        const loading = document.getElementById("spa-loading");
        if (loading) {
            loading.remove();
        }

        // Set up global auth update listener (for when child components update auth)
        this.$watch(
            () => window.__USER_AUTH__,
            () => {
                this.$forceUpdate(); // Force re-render when auth changes
            },
            { deep: true }
        );
    },
    methods: {
        async handleLogout() {
            this.loggingOut = true;
            try {
                // Standard Laravel logout endpoint
                await axios.post("/logout");
                // Clear frontend auth
                window.__USER_AUTH__ = { isLoggedin: false, user: null };
                // Redirect to home
                this.$router.push({ name: "home" });
            } catch (err) {
                console.error("Logout failed:", err);
                // Even if logout fails, clear local auth state
                window.__USER_AUTH__ = { isLoggedin: false, user: null };
                this.$router.push({ name: "home" });
            } finally {
                this.loggingOut = false;
            }
        },
    },
};
</script>

<style>
/* Global styles */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue",
        Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background-color: #f0f2f5;
    color: #333;
}

#app {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: #f0f2f5;
}

/* Navigation Bar */
.navbar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    font-size: 1.5rem;
    font-weight: bold;
}

.nav-logo {
    color: white;
    text-decoration: none;
    transition: opacity 0.2s;
}

.nav-logo:hover {
    opacity: 0.8;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.nav-link {
    color: white;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.2s;
    font-weight: 500;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
}

.nav-link.router-link-active {
    background-color: rgba(255, 255, 255, 0.25);
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-user {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    padding: 0.5rem;
}

.nav-button {
    background-color: #ef4444;
    color: white;
    border: none;
    padding: 0.5rem 1.25rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-button:hover:not(:disabled) {
    background-color: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
}

.nav-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Main Content */
.main-content {
    flex: 1;
    padding: 2rem 1rem;
    background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
    min-height: calc(100vh - 200px);
}

/* Footer */
.footer {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    text-align: center;
    margin-top: auto;
    box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
}

.footer-content p {
    margin: 0;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }

    .nav-links {
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }

    .nav-link,
    .nav-button {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    .main-content {
        padding: 1rem;
    }
}

/* Router transition animations (optional) */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
