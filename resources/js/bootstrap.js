// Import Bootstrap CSS and JS
import 'bootstrap';
import axios from 'axios';

// Make axios available globally
window.axios = axios;

// Set default headers for axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import Bootstrap components explicitly and make them available globally
import { Toast, Modal, Dropdown, Alert, Collapse, Offcanvas } from 'bootstrap';
window.bootstrap = { 
    Toast, 
    Modal, 
    Dropdown, 
    Alert, 
    Collapse, 
    Offcanvas 
};

// Import Laravel Echo and Pusher
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Make Pusher available globally
window.Pusher = Pusher;

// Configure Echo for Pusher cloud service
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true, // Pusher cloud requires TLS
    encrypted: true,
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                })
                .then(response => {
                    callback(false, response.data);
                })
                .catch(error => {
                    console.error('Broadcasting auth error:', error);
                    callback(true, error);
                });
            }
        };
    },
});

console.log('✅ Echo configured for Pusher cloud service');
console.log('✅ Bootstrap components loaded:', Object.keys(window.bootstrap));