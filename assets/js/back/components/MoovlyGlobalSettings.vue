<template>
  <div id="moovly-global-settings" class="mb-3">
    <div class="row">
      <div class="col-12">
        <div class="jumbotron m-0 bg-white" v-if="!ui.loading">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-6 col-lg-4">
                <h2 class="card-title small">Global settings</h2>
              </div>
              <div class="col-12 col-md-6 col-lg-8">
                <form @submit.prevent="submit">
                  <div class="form-group">
                    <label for="localesetting">Current language</label>
                    <select
                      id="localesetting"
                      class="form-control"
                      v-model="state.locale"
                    >
                      <option v-for="locale in locales" :value="locale.value">{{
                        locale.label
                      }}</option>
                    </select>
                  </div>
                  <input
                    type="submit"
                    value="Save Settings"
                    class="btn btn-primary mt-4"
                  />
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" />
<script>
import { supportedLanguages } from "@moovly/i18n";
import util from "../util";
export default {
  props: {
    restApiCall: {
      required: true,
    },
  },
  mounted() {
    this.fetchSettings();
  },
  data() {
    return {
      settings: {
        get: `${this.restApiCall}moovly/v1/settings/all`,
        update: `${this.restApiCall}moovly/v1/settings/update`,
      },
      locales: supportedLanguages,
      state: {
        locale: null,
      },
      ui: {
        loading: false,
        save: {
          success: false,
          error: false,
        },
      },
    };
  },

  computed: {},

  methods: {
    fetchSettings() {
      this.ui.loading = true;
      axios.get(this.settings.get).then((response) => {
        this.state = response.data;
        this.ui.loading = false;
      });
    },
    submit() {
      axios
        .put(this.settings.update, this.state)
        .then(() => {
          this.ui.save.success = true;
          util.toastSuccess("successfully saved");
        })
        .catch(() => {
          this.ui.save.error = false;
          util.toastError("error, unable to save");
        });
    },
  },
};
</script>
