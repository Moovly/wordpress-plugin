<template>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div v-if="!ui.loading && ui.project">
                <img :src="ui.project.thumbnail" alt="">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            id: {
                type: String,
                required: true,
            }
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
