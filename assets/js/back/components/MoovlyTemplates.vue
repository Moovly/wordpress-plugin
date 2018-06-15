<template>
    <div id="moovly-templates">
        <h2 class="mt-3 mb-5">Templates</h2>
        <table class="table" v-if="!ui.loading">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Template name</th>
                    <th scope="col">Shortcode</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(template, index) in ui.templates" :key="template.identifier">
                    <th scope="row">{{ index + 1 }}</th>
                    <th>{{ template.title }}</th>
                    <th><code>{{ template.shortcode }}</code></th>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        mounted() {
            this.fetch();
        },

        data() {
            return {
                ui: {
                    templates: [],
                    loading: false,
                },
                templates: {
                    index: `${window.location.origin}/wp-json/moovly/v1/templates/index`,
                }
            }
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                axios.get(this.templates.index).then(response => {
                    this.ui.templates = response.data;
                    this.ui.loading = false;
                });
            }
        }
    }
</script>