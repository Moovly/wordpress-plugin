<template>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div v-if="!ui.loading && ui.project">
                <div class="embed-responsive embed-responsive-16by9" >
                    <video controls class="embed-responsive-item py-3" :poster="ui.project.thumbnail">
                        <source v-for="render in ui.project.renders" :key="render.id" :src="render.url">
                        Your browser does not support the video tag!
                    </video>
                </div>
            </div>
            <spinner v-else/>
        </div>
    </div>
</template>
<script>
    import Spinner from './../Spinner';
    export default {
        props: {
            id: {
                type: String,
                required: true,
            }
        },

        components: {
            Spinner,
        },

        mounted() {
            this.fetch();
        },

        data() {
            return {
                ui: {
                    project: null,
                    loading: false,
                    error: false,
                }
            }
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                axios.get(`${window.location.origin}/wp-json/moovly/v1/projects/${this.id}`).then(response => {
                    this.ui.loading = false;
                    this.ui.project = response.data;
                }).catch(error => {
                    this.ui.loading = true;
                    this.ui.error = true;
                });
            }
        }
    }
</script>
