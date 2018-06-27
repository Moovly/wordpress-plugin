<template>
    <div v-if="!ui.loading && ui.videos.length" class="embed-responsive embed-responsive-16by9">
        <moovly-video
            v-for="video in ui.videos"
            :key="video.url"
            :src="[video.url]"
            :autoplay="autoplay"
        />
    </div>
</template>
<script>
    import MoovlyVideo from '../MoovlyVideo';

    export default {
        props: {
            postId: {
                required: true,
            },
            autoplay: {
                required: false,
            },
        },

        components: {
            MoovlyVideo,
        },

        data() {
            return {
                ui: {
                    videos: [],
                    loading: false,
                    error: false,
                },
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                axios.get(`${window.location.origin}/wp-json/moovly/v1/post-videos/${this.postId}`)
                .then(response => {
                    this.ui.loading = false;
                    this.ui.videos = response.data.values;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                })
            },

            videoLoaded(index) {
                if(this.autoplay === 'true') {
                    this.$refs['video-' + index][0].play();
                }
            }
        },

    }
</script>
