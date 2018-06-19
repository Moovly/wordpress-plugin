require('./bootstrap');

import MoovlySettings from './components/MoovlySettings';
import MoovlyTemplates from './components/MoovlyTemplates';
import MoovlyProjects from './components/MoovlyProjects';

const moovly = {
    settings: new Vue({
        el: "#wpbody-content",
        components: {
            MoovlySettings,
            MoovlyTemplates,
            MoovlyProjects,
        },
    }),
};

window.moovly = moovly;
