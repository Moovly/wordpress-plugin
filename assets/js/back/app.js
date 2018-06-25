require('./bootstrap');

import Moovly from './components/Moovly';
import MoovlySettings from './components/MoovlySettings';
import MoovlyTemplates from './components/MoovlyTemplates';
import MoovlyProjects from './components/MoovlyProjects';

const moovly = {
    settings: new Vue({
        el: "#wpbody-content",
        components: {
            Moovly,
            MoovlySettings,
            MoovlyTemplates,
            MoovlyProjects,
        },
    }),
};

window.moovly = moovly;
