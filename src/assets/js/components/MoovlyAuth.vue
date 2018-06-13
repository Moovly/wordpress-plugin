<template>
    <div id="moovly-auth">
        <div class="card bg-white" v-if="ui.token.value && !ui.loading">
            <div class="card-body">
                <h2 class="card-title mb-3">Authentication</h2>
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
                <a href="#logout" title="Log out" @click.prevent="logout" class="btn float-right">Log out</a>
            </div>
        </div>
        <div class="row" v-if="!ui.token.value">
            <div class="col-12">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" :src="auth.url + auth.callback"></iframe>
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
                    logout: `${window.location.origin}/wp-json/moovly/v1/auth/logout`,
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

            logout() {
                this.ui.loading = true;
                axios.post(this.auth.logout).then(response => {
                    this.ui.token.value = null;
                    this.ui.loading = false;
                });
            }
        }
    }
</script>
