import MoovlyProject from "./components/shortcodes/MoovlyProject";
import MoovlyPostVideo from "./components/shortcodes/MoovlyPostVideo";
import "promise-polyfill/src/polyfill";

let moovly = {
  shortcodes: {
    templates: [],
    projects: [],
    postVideos: [],
  },
};

const buildElements = (className, type, components) => {
  const classes = document.getElementsByClassName(className);

  for (let index = 0; index < classes.length; index++) {
    let element = classes[index];

    moovly.shortcodes[type].push(
      new Vue({
        el: "#" + element.id,
        components: components,
        name: element.id,
      })
    );
  }
};

buildElements("moovly-post-video", "postVideos", { MoovlyPostVideo });
buildElements("moovly-project", "projects", { MoovlyProject });

window.moovly = moovly;
