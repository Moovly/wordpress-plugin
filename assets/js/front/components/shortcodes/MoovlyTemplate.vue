<template>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="my-5 card p-5">
                <h5 class="card-title">Moovly Template</h5>
                <form action="" v-if="!ui.loading && !ui.error" @submit.prevent="submit">
                    <h6 class="mb-5">{{ ui.template.name }}</h6>
                    <moovly-variable v-model="ui.template.variables[index]" v-for="(variable, index) in ui.template.variables" :key="variable.id"/>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <div class="alert alert-danger my-5" v-if="form.error">
                        <p class="mb-0">Whoops, looks like something went wrong.</p>
                        <p class="mb-0">Please try again later.</p>
                    </div>
                </form>
                <div class="alert alert-danger text-center my-5" v-if="!ui.loading && ui.error">
                    <p class="mb-0"> Whoops, looks like something went wrong.</p>
                    <p class="mb-0">Refresh the application to try again.</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import MoovlyVariable from './../variables/MoovlyVariable';

    export default {
        props: {
            id: {
                type: String,
                required: true,
            }
        },

        components: {
            MoovlyVariable,
        },

        data() {
            return {
                ui: {
                    template: {
                        name: '',
                        variables: [],
                    },
                    loading: false,
                    error: false,
                },
                form: {
                    error: false,
                    loading: false,
                },
                templates: {
                    show: `${window.location.origin}/wp-json/moovly/v1/templates/${this.id}`,
                    save:  `${window.location.origin}/wp-json/moovly/v1/templates/${this.id}/store`,
                }
            }
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                this.ui.loading = true;
                axios.get(this.templates.show).then(response => {
                    this.ui.template.name = response.data.name;
                    this.ui.template.variables = response.data.variables.map(variable => {
                        variable.value = '';
                        return variable;
                    });
                    this.ui.loading = false;
                }).catch(error => {
                    this.ui.loading = false;
                    this.ui.error = true;
                });
            },

            submit() {
                this.form.loading = true;
                let variableValues = this.ui.template.variables.map(variable => {
                    return {
                       [variable.id]: variable.value,
                    }
                });
                axios.post(this.templates.save, {
                    variables: variableValues,
                }).then(response => {
                    this.form.error = false;
                    this.form.loading = false;
                }).catch(error => {
                    this.form.error = true;
                    this.form.loading = false;
                })
            }
        }
    }
</script>
