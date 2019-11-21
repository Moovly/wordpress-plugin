import { MoovlyPlugins } from "@moovly/plugins-wordpress";

const getElementAndRenderCorrectComponent = (className, Component) => {
  const elements = document.getElementsByClassName(className);

  for (let i = 0; i < elements.length; i++) {
    const element = elements[i];
    debugger;
  }
};

getElementAndRenderCorrectComponent("moovly-template");
