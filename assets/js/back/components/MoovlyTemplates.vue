<template>
    <div id="moovly-templates">
        <div class="container-fluid">
            <div class="plugin-header">
                <div class="plugin-header__branding">
                    <img src="/wp-content/plugins/moovly/src/../dist/images/moovly.png" /><h2>Moovly</h2>
                </div>
                <div class="plugin-header__page-name">
                    <h3> > Templates</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-moovly" v-if="!ui.loading">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Template name</th>
                                <th scope="col">Shortcode</th>
                                <th scope="col">Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(template, index) in ui.templates" :key="template.identifier">
                                <th scope="row">{{ index + 1 }}</th>
                                <th><img :src="template.thumbnail" style="max-width: 75px"/></th>
                                <th>{{ template.title }}</th>
                                <th><pre><code>{{ template.shortcode }}</code></pre></th>
                                <th><a :href="template.preview">See preview</a></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
