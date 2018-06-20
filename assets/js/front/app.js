require('./bootstrap');

import MoovlyTemplate from './components/shortcodes/MoovlyTemplate';
import MoovlyProject from './components/shortcodes/MoovlyProject';


const moovly = {
    shortcodes: {
        templates: new Vue({
            el: "#moovly-template",
            components: {
                MoovlyTemplate,
            },
        }),
        projects: new Vue({
            el: "#moovly-project",
            components: {
                MoovlyProject,
            },
        })
    },
};

window.moovly = moovly;
