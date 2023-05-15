import "toastify-js/src/toastify.js";
require("./bootstrap");
import { createApp } from "vue";
import Moovly from "./components/Moovly";
import MoovlySettings from "./components/MoovlySettings";
import MoovlyTemplates from "./components/MoovlyTemplates";
import MoovlyProjects from "./components/MoovlyProjects";
import MoovlyPostVideos from "./components/MoovlyPostVideos";

const RootComponent = {
  components: {
    Moovly,
    MoovlySettings,
    MoovlyTemplates,
    MoovlyProjects,
    MoovlyPostVideos,
  },
};

const app = createApp(RootComponent);
app.mount("#wpbody-content");
