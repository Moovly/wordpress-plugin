<template>
  <div :style="'width:' + width">
    <div
      v-if="!ui.loading && ui.videos && ui.videos.length"
      class="embed-responsive embed-responsive-16by9"
      style="height: inherit;"
    >
      <moovly-video
        v-for="video in ui.videos"
        :key="video.url"
        :src="[video.url]"
        :autoplay="autoplay"
      />
    </div>
    <spinner v-else />
  </div>
</template>
<script>
import MoovlyVideo from "../MoovlyVideo";
import Spinner from "../Spinner";

export default {
  props: {
    postId: {
      required: true
    },
    autoplay: {
      required: false
    },
    width: {
      type: String,
      default: "100%"
    },
    restApiCall: {
      required: true
    }
  },

  components: {
    MoovlyVideo,
    Spinner
  },

  data() {
    return {
      ui: {
        videos: [],
        loading: false,
        error: false
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
        .get(`${this.restApiCall}moovly/v1/post-videos/${this.postId}`)
        .then(response => {
          this.ui.loading = false;
          this.ui.videos = response.data.values;
        })
        .catch(error => {
          this.ui.loading = false;
          this.ui.error = true;
        });
    },

    videoLoaded(index) {
      if (this.autoplay === "true") {
        this.$refs["video-" + index][0].play();
      }
    }
  }
};
</script>
