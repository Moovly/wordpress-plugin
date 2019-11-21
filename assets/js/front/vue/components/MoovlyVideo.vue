<template>
  <div>
    <video
        class="embed-responsive-item py-3"
        :style="'width:' + width"
        controls
        @loadeddata="loaded"
        :poster="poster"
        v-if="src[0] !== null"
    >
      <source
          v-for="(srcLink, index) in src"
          :src="srcLink"
          :key="index"
          type="video/mp4"
      >
      Your browser does not support the video tag!
    </video>
    <spinner v-else/>
  </div>
</template>
<script>
  import Spinner from './Spinner';

  export default {
    components: {
      Spinner,
    },
    props: {
      src: {
        type: Array,
        required: true,
      },
      poster: {
        type: String,
        required: false,
      },
      autoplay: {
        default: false,
      },
      width: {
        type: String,
        default: '100%',
      }
    },

    methods: {
      loaded()
      {
        if (this.autoplay && this.autoplay !== 'false') {
          this.$el.play();
        }
      }
    }
  }
</script>
