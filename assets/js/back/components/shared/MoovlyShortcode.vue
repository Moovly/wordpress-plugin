<template>
      <input type="text" class="w-100 text-sm" readonly :value="shortcode" @focus="select">
</template>
<script>
    export default {
        props: {
            shortcode: {
                type: String,
                required: true,
            }
        },

        methods: {
            select(e) {
                e.target.select();
            }
        }
    }
</script>
