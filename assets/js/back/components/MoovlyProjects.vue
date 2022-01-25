<template>
  <div id="moovly-projects">
    <div class="container-fluid">
      <moovly-header page="Projects" />
      <div class="row">
        <div class="col-12">
          <div class="loader-notice" v-if="ui.loading">
            <div class="loader-wrapper" style="height: 75px">
              <div class="loader"></div>
            </div>

            <h4>Loading your Moovly projects</h4>
            <small
              >ProTip: If you don't know where to start, take a look at our
              webinars</small
            >
          </div>

          <table class="table table-moovly" v-if="!ui.loading">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Thumbnail</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Is rendered</th>
                <th scope="col">Shortcode</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(project, index) in ui.projects"
                :key="project.identifier"
              >
                <td scope="row">{{ index + 1 }}</td>
                <td>
                  <img
                    :src="project.thumbnail"
                    v-if="project.thumbnail"
                    style="max-width: 75px;"
                  />
                </td>
                <td>{{ project.title }}</td>
                <td>{{ project.description }}</td>
                <td>{{ project.last_render_url ? "Yes" : "No" }}</td>
                <td>
                  <moovly-shortcode
                    :shortcode="project.shortcode"
                    v-if="project.last_render_url"
                  />
                </td>
              </tr>
            </tbody>
          </table>
          <button
            v-if="!ui.loading"
            class="btn btn-primary mt-4"
            v-on:click="loadMore"
          >
            Load more
          </button>
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
    this.fetch();
  },

  data() {
    return {
      ui: {
        projects: [],
        page: 1,
        loading: false,
      },
      projects: {
        index: `${this.restApiCall}moovly/v1/projects/index`,
      },
    };
  },

  methods: {
    fetch() {
      this.ui.loading = true;
      axios
        .get(`${this.projects.index}?page=${this.ui.page}`)
        .then((response) => {
          this.ui.projects = response.data;
          this.ui.loading = false;
        });
    },
    loadMore() {
      this.ui.page += 1;
      axios
        .get(`${this.projects.index}?page=${this.ui.page}`)
        .then((response) => {
          response.data.forEach((item) => {
            this.ui.projects.push(item);
          });
        });
    },
  },
};
</script>
