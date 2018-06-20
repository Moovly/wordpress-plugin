<template>
    <div id="moovly-job">
        <div class="alert alert-info text-center my-5" v-if="ui.loading && job && job.id">
            <p class="mb-0">We're currently processing your video...</p>
            <p class="mb-0"></p>
        </div>
        <spinner v-if="ui.loading"></spinner>
        <div class="alert alert-danger text-center my-5" v-if="ui.error && job && job.id">
            <p class="mb-0"> Whoops, looks like something went wrong.</p>
            <p class="mb-0">Refresh the application to try again.</p>
        </div>
        <div v-if="!ui.loading && ui.videos.length" class="embed-responsive embed-responsive-16by9">
            <video controls v-for="video in ui.videos" :key="video.url" class="embed-responsive-item py-3">
                <source :src="video.url" type="video/mp4">
                Your browser does not support the video tag!
            </video>
        </div>
    </div>
</template>
<script>
    import Spinner from './Spinner';

    export default {
        props: {
            job: {
                required: true,
            }
        },

        components: {
            Spinner,
        },

        data() {
            return {
                ui: {
                    videos: [],
                    loading: false,
                    error: false,
                    refreshRate: 4000, //4 seconds
                }
            }
        },

        watch: {
            job: 'poll',
        },

        created() {
            this.poll();
        },

        methods: {
            poll() {
                if(!this.job) return;

                console.log('Polling...');
                this.ui.loading = true;
                axios.get(`${window.location.origin}/wp-json/moovly/v1/jobs/${this.job.id}/status`).then(this.setStatus).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                });
            },

            setStatus(response) {
                if(response.data.status === 'finished') {
                    this.ui.videos = response.data.values;
                    this.ui.loading = false;
                } else {
                    setTimeout(this.poll, this.ui.refreshRate);
                }
            },
        },
    }
</script>
