require('./bootstrap');

import MoovlyTemplate from './components/shortcodes/MoovlyTemplate';
import MoovlyProject from './components/shortcodes/MoovlyProject';
import MoovlyPostVideo from "./components/shortcodes/MoovlyPostVideo";

let moovly = {
  shortcodes: {
    templates: [],
    projects: [],
    postVideos: [],
  },
};

const buildElements = (className, components) => {
  for (const element of document.getElementsByClassName(className)) {
    moovly.shortcodes.postVideos.push(new Vue({
      el: "#" + element.id,
      components: components,
      name: element.id
    }));
  }
};

buildElements('moovly-post-video', {MoovlyPostVideo});
buildElements('moovly-template', {MoovlyTemplate});
buildElements('moovly-project', {MoovlyProject});

window.moovly = moovly;
