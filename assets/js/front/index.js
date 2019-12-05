import "./common/bootstrap";
import "./vue";

import { MoovlyPlugin } from "@moovly/plugins-embed";
MoovlyPlugin.setProxies({
  "fetch-template": "/wp-json/moovly/v1/templates/:id",
  "upload-object": "/wp-json/moovly/v1/objects/upload",
  "template-job-create": "/wp-json/moovly/v1/templates/:id/store",
  "template-job-poll": "/wp-json/moovly/v1/jobs/:id/status"
});
const getElementAndRenderCorrectComponent = (className, Component) => {
  const elements = document.getElementsByClassName(className);
  for (let i = 0; i < elements.length; i++) {
    const element = elements[i];
    element.classList.remove("moovly-template");
    new MoovlyPlugin.Templates.QuickEdit({
      container: element,
      templateId: element.dataset.id,
      withPreview: true,
      pollTillSuccess: true
    });
  }
};

getElementAndRenderCorrectComponent("moovly-template");
