<template>
    <moovly-file-variable
        v-model="value"
        @input="input"
        @change="change"
    >
        <div v-if="variable.value && file" class="embed-responsive embed-responsive-16by9">
            <video controls class="embed-responsive-item py-3">
                <source :src="file.src" :type="file.type">
                Your browser does not support the video tag!
            </video>
        </div>
    </moovly-file-variable>
</template>
<script>
    import MoovlyFileVariable from './MoovlyFileVariable';

    export default {
        props: {
            value: {
                required: true,
            },
        },

        components: {
            MoovlyFileVariable,
        },

        watch: {
            value: 'setValue',
        },

        created() {
            this.setValue();
        },

        data() {
            return {
                variable: null,
                file: null,
            }
        },

        methods: {
            setValue() {
                this.variable = this.value;
            },

            input() {
                this.$emit('input', this.variable);
            },

            change(file) {
                file.src = URL.createObjectURL(file);
                this.file = file;
            },
        }
    }
</script>
