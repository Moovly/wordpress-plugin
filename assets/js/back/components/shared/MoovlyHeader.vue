<template>
    <div class="plugin-header">
        <div class="plugin-header__branding">
            <img :src="headerLogo" /><h2>Moovly</h2>
        </div>
        <div class="plugin-header__page-name" v-if="page"><h3> > {{ page }}</h3></div>
    </div>
</template>
<script>
export default {
    props: {
        page: {
            type: String,
            default: '',
        }
    },

    data() {
        return {
            headerLogo: window.moovlyAssets.logo,
        }
    },
}
</script>
