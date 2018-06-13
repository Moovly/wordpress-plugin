<template>
    <div id="moovly-settings" class="mt-5">
        <div class="container-fluid">
            <h1>Moovly</h1>
            <moovly-auth />
        </div>
    </div>
</template>
<script>
    import MoovlyAuth from './MoovlyAuth';

    export default {
        components: {
            MoovlyAuth,
        }
    }
</script>
