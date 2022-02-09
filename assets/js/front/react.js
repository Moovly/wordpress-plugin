import { parse } from "querystring";
import { MoovlyPlugin } from "@moovly/plugins-embed";

const getElementAndRenderCorrectComponent = (className, rendercomponent) => {
  const elements = document.getElementsByClassName(className);
  if (elements.length) {
    const item = elements[0];
    MoovlyPlugin.setProxies({
      "fetch-template": `${item.dataset.rest}moovly/v1/templates/:id`,
      "upload-object": `${item.dataset.rest}moovly/v1/objects/upload`,
      "template-job-create": `${item.dataset.rest}moovly/v1/templates/:id/store`,
      "template-job-poll": `${item.dataset.rest}moovly/v1/jobs/:id/status`,
      "fetch-templates": `${item.dataset.rest}moovly/v1/templates/index`,
      "fetch-renders-user": `${item.dataset.rest}moovly/v1/renders/generated/index`,
      "fetch-projects": `${item.dataset.rest}moovly/v1/projects/index`,
      "fetch-project": `${item.dataset.rest}moovly/v1/projects/:id`,
      "fetch-project-renders": `${item.dataset.rest}moovly/v1/projects/:id/renders`,
      "remove-project-render": `${item.dataset.rest}moovly/v1/projects/:id/renders/:renderId`,
    });
  }
  for (let i = 0; i < elements.length; i++) {
    const element = elements[i];
    element.classList.remove(className);

    rendercomponent(element);
  }
};

getElementAndRenderCorrectComponent("moovly-template", (element) => {
  let id = element.dataset.id;
  const publishToYoutube = element.dataset.publishToYoutube === "1";
  const createProject = element.dataset.createProject === "1";
  const createRender = element.dataset.createRender === "1";

  if (id === "query") {
    const parsedQuery = parse(window.location.search.substring(1));
    id = parsedQuery.template_id;
  }
  new MoovlyPlugin.Templates.QuickEdit({
    container: element,
    templateId: id,
    withPreview: true,
    publishToYoutube,
    createProject,
    createRender,
    pollTillSuccess: true,
  });
});
MoovlyPlugin.load().then(() => {
  getElementAndRenderCorrectComponent("moovly-templates", (element) => {
    new MoovlyPlugin.Templates.List({
      container: element,
      detailEndpoint: element.dataset.detailEndpoint,
      type: element.dataset.type,
    });
  });

  getElementAndRenderCorrectComponent("moovly-renders", (element) => {
    let projectId = element.dataset.projectId;

    if (projectId && projectId === "query") {
      const parsedQuery = parse(window.location.search.substring(1));
      projectId = parsedQuery.project_id;
    }
    new MoovlyPlugin.Projects.RenderList({
      container: element,
      projectId: projectId,
      viewType: element.dataset.viewType,
      canDelete: element.dataset.allowDelete,
    });
  });
  getElementAndRenderCorrectComponent("moovly-projects", (element) => {
    new MoovlyPlugin.Projects.ProjectList({
      container: element,
      detailEndpoint: element.dataset.detailEndpoint,
    });
  });
  getElementAndRenderCorrectComponent("moovly-project", (element) => {
    let id = element.dataset.id;

    if (id === "query") {
      const parsedQuery = parse(window.location.search.substring(1));
      id = parsedQuery.project_id;
    }
    new MoovlyPlugin.Projects.ProjectPlayer({
      container: element,
      projectId: id,
      withTitleDescription: element.dataset.showTitleDescription,
    });
  });
});
