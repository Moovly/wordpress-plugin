<template>
    <div id="moovly-settings" class="settings">
        <div class="container-fluid">
            <div class="plugin-header">
                <div class="plugin-header__branding">
                    <img src="/wp-content/plugins/moovly/src/../dist/images/moovly.png" /><h2>Moovly</h2>
                </div>
                <div class="plugin-header__page-name"><h3> > Settings</h3></div>
            </div>
            <div class="settings__body">
                <div class="jumbotron jumbotron-light">
                    <div class="row">
                        <div class="col-sm-4">
                            <h4>Getting started</h4>

                            <p>
                                You have installed the Moovly plugin, which will allow you to create automated videos of
                                your visitors input, or will generate videos out of your post content.
                            </p>

                            <p>
                                Getting started is quite easy:
                            </p>
                                <ol>
                                    <li><a href="https://www.moovly.com/sign-up">Create a Moovly account</a></li>
                                    <li><a href="https://developer.moovly.com/docs/personal-access-tokens">Log into the API Hub</a></li>
                                    <li>Create an access token</li>
                                    <li>Paste a shortcode in your posts/pages/...</li>
                                </ol>

                        </div>
                        <div class="col-sm-4">

                        </div>
                        <div class="col-sm-4">
                            <h2>About the plugin</h2>
                            <p>Version: 1.0.0</p>
                            <a href="https://developer.moovly.com/docs/integrations/wordpress" class="btn btn-outline-info btn-block">Documentation</a>
                            <a href="https://developer.moovly.com" class="btn btn-outline-info btn-block">API Hub</a>
                            <a href="https://dashboard.moovly.com" class="btn btn-outline-info btn-block">Moovly</a>
                            <a href="https://help.moovly.com" class="btn btn-outline-info btn-block">Help</a>
                        </div>
                    </div>
                </div>

                <h4 class="settings__divider">Settings</h4>

                <div class="row">
                    <div class="col-12">
                        <moovly-auth />
                    </div>
                </div>
                <div class="col-12">
                    <div class="card bg-white m-0">
                        <div class="card-body">
                            <h2 class="card-title">Templates</h2>
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
