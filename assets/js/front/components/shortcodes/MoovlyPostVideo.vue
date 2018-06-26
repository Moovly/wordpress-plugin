<template>
    <div v-if="!ui.loading && ui.videos.length" class="embed-responsive embed-responsive-16by9">
            <video controls v-for="video in ui.videos" :key="video.url" class="embed-responsive-item py-3">
                <source :src="video.url" type="video/mp4">
                Your browser does not support the video tag!
            </video>
    </div>
</template>
<script>
    export default {
        props: {
            postId: {
                required: true,
            }
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
                    this.ui.videos = response.data.values;
                    this.ui.loading = false;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                })
            }
        },

    }
</script>
