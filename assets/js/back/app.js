require('./bootstrap');

import MoovlySettings from './components/MoovlySettings';
import MoovlyTemplates from './components/MoovlyTemplates';

const moovly = {
    settings: new Vue({
        el: "#wpbody-content",
        components: {
            MoovlySettings,
            MoovlyTemplates,
        },
    }),
};

window.moovly = moovly;
