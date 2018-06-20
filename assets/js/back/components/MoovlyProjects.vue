<template>
    <div id="moovly-projects">
        <div class="container-fluid">
            <h1 class="mt-2">Moovly</h1>
            <h2 class="mt-3 mb-5">Projects</h2>
            <div class="row">
                <div class="col-12">
                    <table class="table" v-if="!ui.loading">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Shortcode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(project, index) in ui.projects" :key="project.identifier">
                                <th scope="row">{{ index + 1 }}</th>
                                <th>{{ project.title }}</th>
                                <th>{{ project.description }}</th>
                                <th><code>{{ project.shortcode }}</code></th>
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
                    projects: [],
                    loading: false,
                },
                projects: {
                    index: `${window.location.origin}/wp-json/moovly/v1/projects/index`,
                }
            }
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                axios.get(this.projects.index).then(response => {
                    this.ui.projects = response.data;
                    this.ui.loading = false;
                });
            }
        }
    }
</script>
