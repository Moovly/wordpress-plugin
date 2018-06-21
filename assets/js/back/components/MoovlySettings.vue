<template>
    <div id="moovly-settings" class="mt-5">
        <div class="container-fluid">
            <h1 class="mb-5">Moovly</h1>
            <div class="row">
                <div class="col-12">
                    <moovly-auth />
                </div>
                <div class="col-12">
                    <div class="card bg-white m-0">
                        <div class="card-body">
                            <h2 class="card-title">Settings</h2>
                            <form @submit.prevent="submit">
                                <div class="form-group form-check">
                                    <input
                                        type="checkbox"
                                        name="create_moov"
                                        id="create_moov"
                                        class="form-check-input"
                                        v-model="settings.jobs.create_moov"
                                        true-value="1"
                                        false-value="0"
                                        >
                                    <label for="create_moov" class="form-check-label mt-1 ml-2">Save template submissions to projects</label>
                                </div>
                                <button class="btn btn-primary mt-4" type="submit">
                                    Save Settings
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import MoovlyAuth from './MoovlyAuth';
    import MoovlyTemplates from './MoovlyTemplates';

    export default {
        components: {
            MoovlyAuth,
            MoovlyTemplates,
        },

        data() {
            return {
                settings: {
                    jobs: {
                        create_moov: false,
                    }
                },
                ui: {
                    loading: false,
                    error: false,
                }
            }
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                axios.get(`${window.location.origin}/wp-json/moovly/v1/jobs/settings`).then(response => {
                    this.settings.jobs.create_moov = response.data;
                    this.ui.loading = false;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                });
            },

            submit() {
                this.ui.loading = true;
                axios.post(`${window.location.origin}/wp-json/moovly/v1/jobs/settings`, {
                    create_moov: this.settings.jobs.create_moov,
                }).then(response => {
                    this.settings.jobs.create_moov = response.data;
                    this.ui.loading = false;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                });
            }
        }
    }
</script>
