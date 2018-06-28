<template>
    <video
        class="embed-responsive-item py-3"
        controls
        @loadeddata="loaded"
        :poster="poster"
    >
        <source
            v-for="(srcLink, index) in src"
            :src="srcLink"
            :key="index"
            type="video/mp4"
        >
        Your browser does not support the video tag!
    </video>
</template>
<script>
    export default {
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
            }
        },

        methods: {
            loaded() {
                if(this.autoplay && this.autoplay !== 'false') {
                    this.$el.play();
                }
            }
        }
    }
</script>
