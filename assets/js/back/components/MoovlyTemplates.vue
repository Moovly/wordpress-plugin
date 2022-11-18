<template>
  <div id="moovly-templates">
    <div class="container-fluid">
      <moovly-header page="Templates" />
      <div class="row">
        <div class="col-12">
          <div class="loader-notice" v-if="ui.loading">
            <div class="loader-wrapper" style="height: 75px">
              <div class="loader"></div>
            </div>

            <h4>Loading your Templates</h4>
            <small>
              ProTip: If you want the form submitters to receive a copy of the
              video, enable "Send result to form submittors" in the Moovly
              Dashboard when creating a template.
            </small>
          </div>
          <div v-if="!ui.loading" class="plugin-header">
            <div>
              <h5 style="margin-bottom: 10px;">Shortcode settings</h5>
              <div class="form-check">
                <input
                  type="checkbox"
                  name="publishToYoutube"
                  id="publishToYoutube"
                  class="form-check-input"
                  v-model="shortcodeSettings.publishToYoutube"
                />
                <label for="publishToYoutube" class="form-check-label">
                  Publish video to youtube
                </label>
              </div>
              <div class="form-check">
                <input
                  type="checkbox"
                  name="createProject"
                  id="createProject"
                  class="form-check-input"
                  v-model="shortcodeSettings.createProject"
                />
                <label for="createProject" class="form-check-label">
                  Create project from template submission
                </label>
              </div>
              <div class="form-check">
                <input
                  type="checkbox"
                  name="createRender"
                  id="createRender"
                  class="form-check-input"
                  v-model="shortcodeSettings.createRender"
                />
                <label for="createRender" class="form-check-label">
                  Create render from template
                </label>
              </div>
              <div class="form-check">
                <input
                  type="checkbox"
                  name="pollTillSuccess"
                  id="pollTillSuccess"
                  class="form-check-input"
                  v-model="shortcodeSettings.pollTillSuccess"
                />
                <label for="pollTillSuccess" class="form-check-label">
                  After submission, poll till video is done and show video
                </label>
              </div>
            </div>
          </div>
          <table
            class="table table-moovly"
            id="table-templates"
            v-if="!ui.loading"
          >
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Thumbnail</th>
                <th scope="col">Template name</th>
                <th scope="col">Shortcode</th>
                <th scope="col" class="text-center">
                  Sends result to form submitter
                </th>
                <th scope="col" class="text-center">Use for posts</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(template, index) in ui.templates"
                :key="template.identifier"
              >
                <td scope="row">{{ index + 1 }}</td>
                <td>
                  <img :src="template.thumbnail" style="max-width: 75px" />
                </td>
                <td>{{ template.title }}</td>
                <td>
                  <moovly-shortcode :shortcode="template.shortcode" />
                </td>
                <td class="text-center">
                  {{ template.is_email_enabled ? "Yes" : "No" }}
                </td>
                <td class="text-center">
                  <input
                    type="radio"
                    :id="template.id"
                    :value="template.identifier"
                    v-model="ui.selectedTemplate"
                    :checked="isChecked(template)"
                    @change="updatePostTemplates"
                    v-if="template.supports_post_automation"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MoovlyHeader from "./shared/MoovlyHeader";
import MoovlyShortcode from "./shared/MoovlyShortcode";

export default {
  props: {
    restApiCall: {
      required: true,
    },
  },
  components: {
    MoovlyHeader,
    MoovlyShortcode,
  },

  mounted() {
    Promise.all([this.fetch(), this.getPostTemplates()]);
  },

  data() {
    return {
      ui: {
        templates: [],
        postTemplates: [],
        selectedTemplate: null,
        loading: false,
      },
      shortcodeSettings: {
        publishToYoutube: false,
        createProject: false,
        createRender: true,
        pollTillSuccess: true,
      },
      templates: {
        index: `${this.restApiCall}moovly/v1/templates/index`,
        settings: `${this.restApiCall}moovly/v1/templates/settings`,
      },
    };
  },
  watch: {
    shortcodeSettings: {
      handler(val) {
        this.setTemplateShortCode();
      },
      deep: true,
    },
  },
  methods: {
    setTemplateShortCode() {
      this.ui.templates = this.ui.templates.map((temp) => ({
        ...temp,
        shortcode: `[moovly-template id='${temp.id}' publish-to-youtube='${
          this.shortcodeSettings.publishToYoutube ? "1" : 0
        }' create-project='${
          this.shortcodeSettings.createProject ? "1" : 0
        }' create-render='${
          this.shortcodeSettings.createRender ? "1" : 0
        }' poll-till-success='${
          this.shortcodeSettings.pollTillSuccess ? "1" : 0
        }' ]`,
      }));
    },
    fetch() {
      this.ui.loading = true;
      return axios.get(this.templates.index).then((response) => {
        this.ui.templates = response.data;
        this.ui.loading = false;
      });
    },

    getPostTemplates() {
      return axios.get(this.templates.settings).then((response) => {
        this.ui.postTemplates = response.data.post_templates;
        this.ui.selectedTemplate = this.ui.postTemplates[0].id;
      });
    },

    updatePostTemplates() {
      axios
        .post(this.templates.settings, {
          post_templates: [this.ui.selectedTemplate],
        })
        .then((response) => {
          this.ui.selectedTemplate = response.data.post_templates[0].id;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    isChecked(template) {
      return !!this.ui.postTemplates.find((postTemplate) => {
        return postTemplate.id === template.identifier;
      });
    },
  },
};
</script>
