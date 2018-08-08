<template>
  <div id="moovly-account" class="mb-3">
    <h2>Account</h2>
    <div v-if="!ui.loading">
      You are currently logged in as {{ ui.user.first_name }} ({{ ui.user.email }})
    </div>
  </div>
</template>
<script>
  export default {
    data()
    {
      return {
        ui: {
          user: {},
          loading: false,
        },
        account: {
          get: `${window.location.origin}/wp-json/moovly/v1/accounts/me`,
        }
      }
    },

    mounted()
    {
      this.fetch();
    },

    methods: {
      fetch()
      {
        this.ui.loading = true;
        axios.get(this.account.get).then(response => {
          this.ui.user = response.data;
          this.ui.loading = false;
        });
      }
    }
  }
</script>
