<template>
    <div class="form-group">
        <label :for="variable.id"> {{ variable.name}} </label>
        <input
            type="file"
            :accept="accept"
            class="form-control-file"
            @change="setObject"
            required
            :disabled="disabled"
            v-if="!ui.object"
        >
        <div v-else>
            <slot></slot>
            <button type="button" @click="resetObject" class="btn">Reset Image</button>
        </div>
        <p v-if="ui.loading && ui.file.name"> Uploading file {{ ui.file.name }}...</p>
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
                ui: {
                    file: null,
                    object: null,
                    loading: false,
                    error: false,
                },
            }
        },

        computed: {
            accept: function () {
                return `${this.variable.type}/*`;
            },

            disabled: function () {
                return this.ui.loading;
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

            setObject(event) {
                const file = event.target.files[0];
                const formData = new FormData();
                formData.append('file', file);

                this.ui.file = {
                    name: file.name,
                };
                this.ui.loading = true;

                axios.post(`${window.location.origin}/wp-json/moovly/v1/objects/upload-${this.variable.type}`, formData, {
                    headers: {'Content-Type': 'multipart/form-data'},
                }).then(response => {
                    this.ui.object = response.data;
                    this.variable.value = this.ui.object;
                    this.ui.loading = false;
                    this.input();
                }).catch(error => {
                    this.ui.error = true;
                });
            },

            resetObject() {
                this.ui.file = null;
                this.ui.object = null;
                this.variable.value = null;
                this.input();
            },

            input() {
                this.$emit('input', this.variable);
            }
        }
    }
</script>
