require('./bootstrap');

import MoovlyTemplate from './components/shortcodes/MoovlyTemplate';


const moovly = {
    shortcodes: new Vue({
        el: "#moovly-template",
        components: {
            MoovlyTemplate,
        },
    }),
};

window.moovly = moovly;
