import { MoovlyPlugin } from "@moovly/plugins-embed";

const getElementAndRenderCorrectComponent = (className, Component) => {
  const elements = document.getElementsByClassName(className);
  for (let i = 0; i < elements.length; i++) {
    const element = elements[i];
    MoovlyPlugin.setProxies({
      "fetch-template": "/wp-json/moovly/v1/templates/:id"
    });
    MoovlyPlugin.Templates.QuickEdit({
      containerId: element.id,
      templateId: element.dataset.id
    });
  }
};

getElementAndRenderCorrectComponent("moovly-template");
