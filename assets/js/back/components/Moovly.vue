<template>
    <div id="moovly">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <moovly-header/>
                </div>
                <div class="col-12">
                    <moovly-introduction />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import MoovlyHeader from './shared/MoovlyHeader';
    import MoovlyIntroduction from './copy/MoovlyIntroduction';

    export default {
        components: {
            MoovlyHeader,
            MoovlyIntroduction
        }
    }
</script>
