<template>
    <div id="moovly-post-videos">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <moovly-header page="Post Videos" />
                </div>
                <div class="col-12">
                    <table class="table table-moovly" v-if="!ui.loading">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Post title</th>
                                <th scope="col">Status</th>
                                <th scope="col">Shortcode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(video, index) in ui.videos" :key="video.id">
                                <th scope="row">{{ index + 1 }}</th>
                                <th><a :href="video.url">{{ video.title }}</a></th>
                                <th>{{ video.job.status }}</th>
                                <th>
                                    <span v-if="!video.job.id">Not available</span>
                                    <span v-for="(value, index) in video.job.values" :key="index"> {{ value.shortcode }}</span>
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
    import MoovlyHeader from './shared/MoovlyHeader';

    export default {
        components: {
            MoovlyHeader,
        },
        data() {
            return {
                ui: {
                    videos: [],
                    loading: false,
                    error: false,
                },
                videos: {
                    index: `${window.location.origin}/wp-json/moovly/v1/post-videos/index`,
                    status: `${window.location.origin}/wp-json/moovly/v1/post-videos/status`,
                }
            }
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                axios.get(this.videos.index).then(response => {
                    this.ui.loading = false;
                    this.ui.videos = response.data;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                })
            }
        }
    }
</script>
