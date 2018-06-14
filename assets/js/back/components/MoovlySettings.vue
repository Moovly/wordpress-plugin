<template>
    <div id="moovly-settings" class="mt-5">
        <div class="container-fluid">
            <h1>Moovly</h1>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <moovly-auth />
                </div>
                <div class="col-12 col-lg-8">
                    <moovly-templates />
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
        }
    }
</script>
