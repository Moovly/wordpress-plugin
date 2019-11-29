require("../common/bootstrap");

import MoovlyTemplate from "./components/shortcodes/MoovlyTemplate";
import MoovlyProject from "./components/shortcodes/MoovlyProject";
import MoovlyPostVideo from "./components/shortcodes/MoovlyPostVideo";
import "promise-polyfill/src/polyfill";

let moovly = {
  shortcodes: {
    templates: [],
    projects: [],
    postVideos: []
  }
};

const buildElements = (className, components) => {
  const classes = document.getElementsByClassName(className);

  for (let index = 0; index < classes.length; index++) {
    let element = classes[index];

    moovly.shortcodes.postVideos.push(
      new Vue({
        el: "#" + element.id,
        components: components,
        name: element.id
      })
    );
  }
};

buildElements("moovly-post-video", { MoovlyPostVideo });
buildElements("moovly-template", { MoovlyTemplate });
buildElements("moovly-project", { MoovlyProject });

window.moovly = moovly;
