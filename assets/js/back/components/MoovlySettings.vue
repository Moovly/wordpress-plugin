<template>
    <div id="moovly-settings" class="settings">
        <div class="container-fluid">
            <moovly-header page="Settings" />
            <div class="settings__body pt-5">
                <h4 class="settings__divider">Settings</h4>

                <div class="row">
                    <div class="col-12">
                        <moovly-auth />
                    </div>
                    <div class="col-12">
                        <div class="jumbotron bg-white m-0">
                            <div class="card-body">
                                <h2 class="card-title mb-5">Templates</h2>
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4"></div>
                                    <div class="col-12 col-md-6 col-lg-8">
                                        <form @submit.prevent="submit" class="w-50">
                                            <div class="form-group">
                                                <label for="quality">Video Quality</label>
                                                <select name="quality" id="quality" v-model="settings.jobs.quality" class="form-control">
                                                    <option v-for="quality in settings.jobs.options" :key="quality.value" :value="quality.value">{{ quality.text }}</option>
                                                </select>
                                            </div>
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

            </div>
        </div>
    </div>
</template>
<script>
    import MoovlyAuth from './MoovlyAuth';
    import MoovlyTemplates from './MoovlyTemplates';
    import MoovlyHeader from './shared/MoovlyHeader';

    export default {
        components: {
            MoovlyAuth,
            MoovlyTemplates,
            MoovlyHeader,
        },

        data() {
            return {
                settings: {
                    jobs: {
                        create_moov: false,
                        quality: '480p',
                        options: [
                            {value: '480p', text: '480p'},
                            {value: '720p', text: '720p'},
                            {value: '1080p', text: '1080p'},
                        ],
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
                    this.settings.jobs.create_moov = response.data.create_moov;
                    this.settings.jobs.quality = response.data.quality;
                    this.ui.loading = false;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                });
            },

            submit() {
                this.ui.loading = true;
                axios.post(`${window.location.origin}/wp-json/moovly/v1/jobs/settings`, {
                    quality: this.settings.jobs.quality,
                    create_moov: this.settings.jobs.create_moov,
                }).then(response => {
                    this.settings.jobs.create_moov = response.data.create_moov;
                    this.settings.jobs.quality = response.data.quality;
                    this.ui.loading = false;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                });
            }
        }
    }
</script>
