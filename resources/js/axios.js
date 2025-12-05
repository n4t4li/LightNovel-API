import axios from "axios";

// Normalize base URL and avoid accidentally including a trailing `/api`.
// Prefer the current page origin at runtime so cookie domains always match
// the host the browser is using. Only use a build-time env var when explicitly
// configured and the runtime origin is not available.
const envBase = process.env.MIX_API_BASE_URL || (typeof import.meta !== 'undefined' && import.meta.env?.VITE_API_BASE_URL);
const pageOrigin = (typeof window !== 'undefined' && window.location && window.location.origin) ? window.location.origin : null;
const baseURL = String(pageOrigin || envBase || 'http://127.0.0.1:8000').replace(/\/api\/?$/i, '');

const api = axios.create({
    baseURL,
    withCredentials: true,
});

// Ensure axios sends the XSRF token header even if automatic detection fails.
api.interceptors.request.use((config) => {
    try {
        const match = document.cookie.match(new RegExp('(^|;)\\s*XSRF-TOKEN\\s*=\\s*([^;]+)'));
        if (match) {
            config.headers['X-XSRF-TOKEN'] = decodeURIComponent(match[2]);
        }
    } catch (e) {
        // ignore; axios will still try its default behavior
    }
    return config;
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
