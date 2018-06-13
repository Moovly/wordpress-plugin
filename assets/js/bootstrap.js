window.Vue = require('vue');
window.axios = require('axios');

axios.defaults.headers.common['X-WP-Nonce'] = window.moovlyApiSettings.nonce;
