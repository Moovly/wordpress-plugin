<template>
  <div id="moovly-post-videos">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <moovly-header page="Post Videos" />
        </div>
        <div class="col-12">
          <div class="loader-notice" v-if="ui.loading">
            <div class="loader-wrapper" style="height: 75px">
              <div class="loader"></div>
            </div>

            <h4>Loading your Post Videos</h4>
            <small>ProTip: Make sure your templates have correct template variables if you want to use Post Videos</small>
          </div>

          <div v-if="ui.videos.length === 0 && !ui.loading">
            <moovly-no-post-videos />
          </div>

          <table class="table table-moovly" id="table-post-videos" v-if="!ui.loading && ui.videos.length > 0">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Post title</th>
                <th scope="col">Template</th>
                <th scope="col">Status</th>
                <th scope="col">Shortcode</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(video, index) in ui.videos" :key="video.id">
                <th scope="row">{{ index + 1 }}</th>
                <th>
                  <a :href="video.url">{{ video.title }}</a>
                </th>
                <th>{{ video.job.template }}</th>
                <th>{{ video.job.status }}</th>
                <th>
                  <span v-if="!video.job.id">Not available</span>
                  <span v-for="(value, index) in video.job.values" :key="index">
                    <moovly-shortcode :shortcode="value.shortcode" />
                  </span>
                </th>
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
import MoovlyNoPostVideos from "./copy/MoovlyNoPostVideos";

export default {
  props: {
    restApiCall: {
      required: true
    }
  },
  components: {
    MoovlyHeader,
    MoovlyShortcode,
    MoovlyNoPostVideos
  },
  data() {
    return {
      ui: {
        videos: [],
        loading: false,
        error: false
      },
      videos: {
        index: `${this.restApiCall}moovly/v1/post-videos/index`,
        status: `${this.restApiCall}moovly/v1/post-videos/status`
      }
    };
  },

  mounted() {
    this.fetch();
  },

  methods: {
    fetch() {
      this.ui.loading = true;
      axios
        .get(this.videos.index)
        .then(response => {
          this.ui.loading = false;
          this.ui.videos = response.data;
        })
        .catch(error => {
          this.ui.loading = false;
          this.ui.error = true;
        });
    }
  }
};
</script>
