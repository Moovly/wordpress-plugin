<template>
    <div class="form-group">
        <label :for="variable.id"> {{ variable.name }}</label>
        <input
            v-if="variable.type === 'text' && !variable.requirements.multiline"
            :type="variable.type"
            :id="variable.id"
            v-model="variable.value"
            @input="input"
            class="form-control"
            :placeholder="'Minimum: ' + variable.requirements.minimum_length + ', Maximum: ' + variable.requirements.maximum_length"
            required
        >
        <textarea
            v-if="variable.type === 'text' && variable.requirements.multiline"
            :id="variable.id"
            class="form-control"
            v-model="variable.value"
            @input="input"
            :placeholder="'Minimum: ' + variable.requirements.minimum_length + ', Maximum: ' + variable.requirements.maximum_length"
            required
        ></textarea>
    </div>
</template>
<script>
    export default {
        props: {
            value: {
                required: true,
            },
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
