<template>
        <component
            :is="'moovly-' + variable.type"
            v-model="variable"
            @input="input"
        />
</template>
<script>
    import TextVariable from './variables/MoovlyTextVariable';
    import VideoVariable from './variables/MoovlyVideoVariable';
    import ImageVariable from './variables/MoovlyImageVariable';

    export default {
        props: {
            value: {
                required: true,
            },
        },

        components: {
            'moovly-text' : TextVariable,
            'moovly-video': VideoVariable,
            'moovly-image': ImageVariable,
        },

        data() {
            return {
                variable: null,
            }
        },

        watch: {
            value: 'setValue',
        },

        created() {
            this.setValue();
        },

        methods: {
            setValue() {
                this.variable = this.value;
            },

            input() {
                this.$emit('input', this.variable);
            }
        }
    }
</script>
