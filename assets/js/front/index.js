import "./common/bootstrap";
import "./vue";

import { MoovlyPlugin } from "@moovly/plugins-embed";

const getElementAndRenderCorrectComponent = (className, Component) => {
  const elements = document.getElementsByClassName(className);
  if (elements.length) {
    const item = elements[0];
    MoovlyPlugin.setProxies({
      "fetch-template": `${item.dataset.rest}moovly/v1/templates/:id`,
      "upload-object": `${item.dataset.rest}moovly/v1/objects/upload`,
      "template-job-create": `${item.dataset.rest}moovly/v1/templates/:id/store`,
      "template-job-poll": `${item.dataset.rest}moovly/v1/jobs/:id/status`,
    });
  }
  for (let i = 0; i < elements.length; i++) {
    const element = elements[i];
    element.classList.remove("moovly-template");
    new MoovlyPlugin.Templates.QuickEdit({
      container: element,
      templateId: element.dataset.id,
      withPreview: true,
      pollTillSuccess: true,
    });
  }
};

getElementAndRenderCorrectComponent("moovly-template");
