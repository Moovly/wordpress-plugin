<template>
    <div id="moovly-template">

    </div>
</template>
<script>
    export default {
        props: {
            id: {
                type: String,
                required: true,
            }
        },

        data() {
            return {
                //
            }
        },

        mounted() {
            console.log('Render template with id', this.id);
        }
    }
</script>
