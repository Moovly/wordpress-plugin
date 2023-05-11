<template>
  <div id="moovly-settings" class="settings">
    <div class="container-fluid">
      <moovly-header page="Settings" />
      <div class="settings__body pt-5">
        <h4 class="settings__divider">Settings</h4>

        <div class="row">
          <div class="col-12">
            <moovly-auth :rest-api-call="restApiCall" />
          </div>
          <div class="col-12">
            <moovly-global-settings :rest-api-call="restApiCall" />
          </div>
          <div class="col-12 mb-3">
            <div id="moovly-template-settings" class="jumbotron bg-white m-0">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-6 col-lg-4">
                    <h2 class="card-title small">Templates</h2>
                  </div>
                  <div class="col-12 col-md-6 col-lg-8">
                    <form @submit.prevent="submit" class="w-60">
                      <div class="form-group">
                        <label for="quality">Video Quality</label>
                        <select
                          name="quality"
                          id="quality"
                          v-model="settings.jobs.quality"
                          class="form-control"
                        >
                          <option
                            v-for="quality in settings.jobs.options"
                            :key="quality.value"
                            :value="quality.value"
                            >{{ quality.text }}</option
                          >
                        </select>
                      </div>
                      <div class="form-check mb-4">
                        <input
                          type="checkbox"
                          name="create_moov"
                          id="create_moov"
                          class="form-check-input"
                          v-model="settings.jobs.create_moov"
                          true-value="1"
                          false-value="0"
                        />
                        <label for="create_moov" class="form-check-label">
                          Save template submissions to projects
                        </label>
                      </div>
                      <div class="form-check">
                        <input
                          type="checkbox"
                          name="email_form_submission_checkbox"
                          id="email_form_submission_checkbox"
                          class="form-check-input"
                          v-model="ui.email_form_submission_checkbox"
                        />
                        <label
                          for="email_form_submission_checkbox"
                          class="form-check-label"
                        >
                          Send email notification for every template submission
                        </label>
                      </div>
                      <div
                        v-if="ui.email_form_submission_checkbox"
                        class="form"
                      >
                        <input
                          type="text"
                          name="email_form_submission"
                          id="email_form_submission"
                          placeholder="Email"
                          class="form-control"
                          v-model="settings.jobs.email_form_submission"
                        />
                        <small class="form-text text-muted"
                          >use a comma to separate multiple email
                          addresses</small
                        >
                      </div>

                      <button class="btn btn-primary mt-4" type="submit">
                        Save Settings
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <moovly-permissions :rest-api-call="restApiCall" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MoovlyAuth from "./MoovlyAuth";
import MoovlyTemplates from "./MoovlyTemplates";
import MoovlyHeader from "./shared/MoovlyHeader";
import MoovlyPermissions from "./MoovlyPermissions";
import MoovlyGlobalSettings from "./MoovlyGlobalSettings";
import util from "../util";

export default {
  components: {
    MoovlyAuth,
    MoovlyTemplates,
    MoovlyHeader,
    MoovlyPermissions,
    MoovlyGlobalSettings,
  },
  props: {
    restApiCall: {
      required: true,
    },
  },
  data() {
    return {
      settings: {
        jobs: {
          create_moov: false,
          email_form_submission: null,
          quality: "480p",
          options: [
            { value: "480p", text: "480p" },
            { value: "720p", text: "720p" },
            { value: "1080p", text: "1080p" },
          ],
        },
      },
      ui: {
        loading: false,
        error: false,
        email_form_submission_checkbox: false,
      },
    };
  },

  mounted() {
    this.fetch();
  },

  methods: {
    fetch() {
      this.ui.loading = true;
      axios
        .get(`${this.restApiCall}moovly/v1/jobs/settings`)
        .then((response) => {
          this.settings.jobs.create_moov = response.data.create_moov;
          this.settings.jobs.quality = response.data.quality;
          this.settings.jobs.email_form_submission =
            response.data.email_form_submission || null;
          if (this.settings.jobs.email_form_submission) {
            this.ui.email_form_submission_checkbox = true;
          }
          this.ui.loading = false;
        })
        .catch((error) => {
          this.ui.loading = false;
          this.ui.error = true;
        });
    },

    submit() {
      this.ui.loading = true;
      const emailFormSubmission = this.ui.email_form_submission_checkbox
        ? this.settings.jobs.email_form_submission
        : null;
      axios
        .post(`${this.restApiCall}moovly/v1/jobs/settings`, {
          quality: this.settings.jobs.quality,
          email_form_submission: emailFormSubmission,
          create_moov: this.settings.jobs.create_moov,
        })
        .then((response) => {
          this.settings.jobs.create_moov = response.data.create_moov;
          this.settings.jobs.quality = response.data.quality;
          this.settings.jobs.email_form_submission =
            response.data.email_form_submission || null;
          if (this.settings.jobs.email_form_submission) {
            this.ui.email_form_submission_checkbox = true;
          }
          this.ui.loading = false;
          util.toastSuccess("successfully saved template settings");
        })
        .catch((error) => {
          this.ui.loading = false;
          this.ui.error = true;
          util.toastError("error, unable to save template settings");
        });
    },
  },
};
</script>
