<template>
    <div id="moovly-auth" class="mb-3">
        <div class="row">
            <div class="col-12">
                <div class="jumbotron m-0 bg-white" v-if="!ui.loading">
                    <div class="card-body">
                        <h2 class="card-title mb-5">Authentication</h2>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <h2 class="h6">Access tokens</h2>
                                <p>
                                Access tokens are our mechanism of veryfing who you are, in order to get your templates and projects.
                                To get an access token, you'll need to visit our API Hub (developer.moovly.com) and head over to the <a href="https://developer.moovly.com/docs/personal-access-tokens" target="_blank">Personal Access Token</a>
                                section of the documentation.
                                </p>

                                <p>You need to have a Moovly account in order to create access tokens. If you do not have an account yet, head over to our <a href="https://www.moovly.com/sign-up" target="_blank">registration page</a> and sign up.</p>
                                <p>Personal access tokens are long lived, but do expire. When this happens, you will have to create a new one. You can see how long your tokens are valid in the <a href="https://developer.moovly.com/docs/personal-access-tokens">Personal Access Token</a> section</p>
                            </div>
                            <div class="col-12 col-md-6 col-lg-8">
                                <form @submit.prevent="saveToken">
                                    <label for="token">Access Token</label>
                                    <div class="input-group mb-5">
                                        <input class="form-control" :type="tokenInputType" v-model="ui.token.value">
                                        <div class="input-group-append">
                                            <a class="input-group-text" href="#" title="Show access token" @click.prevent="toggleToken">
                                                <svg v-if="ui.token.show" class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.81 4.36l-1.77 1.78a4 4 0 0 0-4.9 4.9l-2.76 2.75C2.06 12.79.96 11.49.2 10a11 11 0 0 1 12.6-5.64zm3.8 1.85c1.33 1 2.43 2.3 3.2 3.79a11 11 0 0 1-12.62 5.64l1.77-1.78a4 4 0 0 0 4.9-4.9l2.76-2.75zm-.25-3.99l1.42 1.42L3.64 17.78l-1.42-1.42L16.36 2.22z"/></svg>
                                                <svg v-else  class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Token</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import MoovlyAccount from './MoovlyAccount';

    export default {
        components: {
            MoovlyAccount,
        },

        mounted() {
            this.checkToken();
        },

        data() {
            return {
                auth: {
                    url: "https://oauth.services.moovly.com/login?callback=",
                    callback: `${window.location.origin}/wp-json/moovly/v1/auth/callback?_wpnonce=${window.moovlyApiSettings.nonce}`,
                    token: `${window.location.origin}/wp-json/moovly/v1/auth/token`,
                },
                ui: {
                    loading: false,
                    token: {
                        value: null,
                        show: false,
                    }
                },
            }
        },

        computed: {
            tokenInputType: function () {
                return this.ui.token.show ? 'text' : 'password';
            }
        },

        methods: {
            checkToken() {
                this.ui.loading = true;
                axios.get(this.auth.token).then(response => {
                    this.ui.token.value = response.data;
                    this.ui.loading = false;
                });
            },

            toggleToken() {
                this.ui.token.show = !this.ui.token.show;
            },

            saveToken() {
                this.ui.loading = true;
                axios.post(this.auth.token, {
                    token: this.ui.token.value
                }).then(response => {
                    this.ui.loading = false;
                    location.reload();
                });
            }
        }
    }
</script>
