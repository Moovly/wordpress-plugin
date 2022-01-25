<template>
  <div id="moovly-permissions" class="mb-3">
    <div class="row">
      <div class="col-12">
        <div class="jumbotron m-0 bg-white" v-if="!ui.loading">
          <div class="card-body">
            <h2 class="card-title mb-5">Permissions</h2>
            <div class="row">
              <div class="col-12 col-md-6 col-lg-4"></div>
              <div class="col-12 col-md-6 col-lg-8">
                <h5>Enabled shortcodes</h5>
                <form @submit.prevent="submit">
                  <div
                    v-for="permission in permissionItems"
                    :key="permission.label"
                  >
                    <div class="form-check">
                      <input
                        type="checkbox"
                        :name="permission.label"
                        :id="permission.label"
                        class="form-check-input"
                        v-model="permission.value"
                      />
                      <label :for="permission.label" class="form-check-label">
                        {{ permission.label }}
                      </label>
                    </div>
                  </div>
                  <input
                    type="submit"
                    value="Save permissions"
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
<script>
export default {
  props: {
    restApiCall: {
      required: true,
    },
  },
  mounted() {
    this.fetchPermissions();
  },
  data() {
    return {
      permissions: {
        shortcodes: `${this.restApiCall}moovly/v1/permissions/shortcodes`,
      },
      permissionItems: [],
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
    fetchPermissions() {
      this.ui.loading = true;
      axios.get(this.permissions.shortcodes).then((response) => {
        this.permissionItems = Object.keys(response.data).map((key) => ({
          label: key,
          value: response.data[key],
        }));
        this.ui.loading = false;
      });
    },
    submit() {
      const permissions = this.permissionItems.reduce((result, permission) => {
        console.log(permission.value);
        result[permission.label] = permission.value;
        return result;
      }, {});
      axios
        .put(this.permissions.shortcodes, { permissions })
        .then(() => {
          this.ui.save.success = true;
        })
        .catch(() => {
          this.ui.save.error = false;
        });
    },
  },
};
</script>
