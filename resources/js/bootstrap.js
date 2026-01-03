import axios from 'axios';
import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Only initialize Echo if Reverb environment variables are set
if (import.meta.env.VITE_REVERB_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        wssPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
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
