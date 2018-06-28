require('./bootstrap');

import Moovly from './components/Moovly';
import MoovlySettings from './components/MoovlySettings';
import MoovlyTemplates from './components/MoovlyTemplates';
import MoovlyProjects from './components/MoovlyProjects';
import MoovlyPostVideos from './components/MoovlyPostVideos';

const moovly = {
    settings: new Vue({
        el: "#wpbody-content",
        components: {
            Moovly,
            MoovlySettings,
            MoovlyTemplates,
            MoovlyProjects,
            MoovlyPostVideos,
        },
    }),
};

window.moovly = moovly;
