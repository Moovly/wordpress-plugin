<template>
    <div id="moovly-templates">
        <div class="container-fluid">
             <moovly-header page="Templates" />
            <div class="row">
                <div class="col-12">
                    <table class="table table-moovly" v-if="!ui.loading">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Template name</th>
                                <th scope="col">Shortcode</th>
                                <th scope="col" class="text-center">Use for posts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(template, index) in ui.templates" :key="template.identifier">
                                <th scope="row">{{ index + 1 }}</th>
                                <th><img :src="template.thumbnail" style="max-width: 75px"/></th>
                                <th>{{ template.title }}</th>
                                <th><pre><code>{{ template.shortcode }}</code></pre></th>
                                <th class="text-center">
                                    <input
                                        type="radio"
                                        :id="template.id"
                                        :value="template.identifier"
                                        v-model="ui.selectedTemplate"
                                        :checked="isChecked(template)"
                                        @change="updatePostTemplates"
                                    >
                                    </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import MoovlyHeader from './shared/MoovlyHeader';

    export default {
        components: {
            MoovlyHeader,
        },

        mounted() {
            Promise.all([
                this.fetch(),
                this.getPostTemplates(),
            ]);
        },

        data() {
            return {
                ui: {
                    templates: [],
                    postTemplates: [],
                    selectedTemplate: null,
                    loading: false,
                },
                templates: {
                    index: `${window.location.origin}/wp-json/moovly/v1/templates/index`,
                    settings: `${window.location.origin}/wp-json/moovly/v1/templates/settings`,
                },
            }
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                return axios.get(this.templates.index)
                .then(response => {
                    this.ui.templates = response.data;
                    this.ui.loading = false;
                });
            },

            getPostTemplates() {
                return axios.get(this.templates.settings)
                .then(response => {
                    this.ui.postTemplates = response.data.post_templates;
                    this.ui.selectedTemplate = this.ui.postTemplates[0].id;
                });
            },

            updatePostTemplates() {
                axios.post(this.templates.settings, {
                    post_templates: [
                        this.ui.selectedTemplate,
                    ],
                }).then(response => {
                    this.ui.selectedTemplate = response.data.post_templates[0].id;
                }).catch(error => {
                    console.log(error);
                })
            },

            isChecked(template) {
                return !! this.ui.postTemplates.find(postTemplate => {
                    return postTemplate.id === template.identifier;
                });
            }
        }
    }
</script>
