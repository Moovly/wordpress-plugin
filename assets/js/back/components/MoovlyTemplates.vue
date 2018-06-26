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
    import MoovlyHeader from './shared/MoovlyHeader';

    export default {
        components: {
            MoovlyHeader,
        },

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
