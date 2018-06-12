require('./bootstrap');

import MoovlySettings from './components/MoovlySettings';

const moovly = {
    settings: new Vue({
        el: "#wpbody-content",
        components: {
            MoovlySettings,
        },
    }),
};

window.moovly = moovly;
