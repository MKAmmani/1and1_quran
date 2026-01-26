import axios from 'axios';
import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Get CSRF token from meta tag
/*let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Ensure cookies (session) are sent with requests from Vite dev server so /broadcasting/auth works
window.axios.defaults.withCredentials = true; */

// Only initialize Echo if Reverb environment variables are set
if (import.meta.env.VITE_REVERB_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        wssPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
        enabledTransports: ['ws', 'wss'],
        // Ensure cookies are sent for authentication
        withCredentials: true,
    });
} else {
    // Fallback for development without Reverb
    window.Echo = {
        channel: function() {
            return {
                listen: function() {
                    console.warn('Reverb not configured. Chat functionality will not work.');
                    return this;
                },
                stop: function() {}
            };
        },
        private: function() {
            return {
                listen: function() {
                    console.warn('Reverb not configured. Chat functionality will not work.');
                    return this;
                },
                stop: function() {}
            };
        }
    };
}
