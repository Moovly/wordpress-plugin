<template>
  <div id="moovly-projects">
    <div class="container-fluid">
      <moovly-header page="Projects"/>
      <div class="row">
        <div class="col-12">
          <div class="loader-notice" v-if="ui.loading">
            <div class="loader-wrapper" style="height: 75px">
              <div class="loader"></div>
            </div>

            <h4>Loading your Moovly projects</h4>
            <small>ProTip: If you don't know where to start, take a look at our webinars</small>
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
            <tr v-for="(project, index) in ui.projects" :key="project.identifier">
              <td scope="row">{{ index + 1 }}</td>
              <td><img :src="project.thumbnail" v-if="project.thumbnail" style="max-width: 75px;"/></td>
              <td>{{ project.title }}</td>
              <td>{{ project.description }}</td>
              <td>{{project.renders.length > 0 ? "Yes" : "No"}}</td>
              <td>
                <moovly-shortcode :shortcode="project.shortcode" v-if="project.renders.length > 0"/>
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
  import MoovlyHeader from './shared/MoovlyHeader';
  import MoovlyShortcode from './shared/MoovlyShortcode';

  export default {
    components: {
      MoovlyHeader,
      MoovlyShortcode,
    },

    mounted()
    {
      this.fetch();
    },

    data()
    {
      return {
        ui: {
          projects: [],
          loading: false,
        },
        projects: {
          index: `${window.location.origin}/wp-json/moovly/v1/projects/index`,
        }
      }
    },

    methods: {
      fetch()
      {
        this.ui.loading = true;
        axios.get(this.projects.index).then(response => {
          this.ui.projects = response.data;
        this.ui.loading = false;
      })
        ;
      }
    }
  }
</script>
