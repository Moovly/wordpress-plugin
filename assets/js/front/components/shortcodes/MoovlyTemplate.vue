<template>
  <div class="row justify-content-center">
    <div :style="'width:' + width">
      <div class="my-5 card p-5">
        <moovly-video
            v-if="!ui.loading && !job.id && ui.template.preview && ui.template.preview.url !== null"
            :src="[ui.template.preview.url]"
        />
        <moovly-job :job="job"></moovly-job>
        <div class="alert alert-danger text-center my-5" v-if="!ui.loading && ui.error">
          <p class="mb-0">{{ui.error_message}}</p>
        </div>
        <form action="" v-if="!ui.loading && !job.id" @submit.prevent="submit">
          <h6 class="mb-5">{{ ui.template.name }}</h6>
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" v-model="job.name" class="form-control"
                   placeholder="Give it a name">
          </div>
          <moovly-variable v-model="ui.template.variables[index]" v-for="(variable, index) in ui.template.variables"
                           :key="variable.id"/>
          <button type="submit" class="btn btn-primary">Submit</button>
          <div class="alert alert-danger my-5" v-if="form.error">
            <p class="mb-0">Whoops, looks like something went wrong.</p>
            <p class="mb-0">Please try again later.</p>
          </div>
        </form>
        <div class="alert alert-info my-5 text-center" v-if="ui.loading && form.processing">
          <p class="mb-0">Submitting your data...</p>
        </div>
        <spinner v-if="ui.loading && !job.id"/>
      </div>
    </div>
  </div>
</template>
<script>
  import MoovlyVariable from './../MoovlyVariable';
  import MoovlyJob from './../MoovlyJob';
  import Spinner from './../Spinner';
  import MoovlyVideo from './../MoovlyVideo';

  export default {
    props: {
      id: {
        type: String,
        required: true,
      },
      width: {
        type: String,
        default: '100%',
      }
    },

    components: {
      MoovlyVariable,
      MoovlyVideo,
      MoovlyJob,
      Spinner,
    },

    data()
    {
      return {
        ui: {
          template: {
            name: '',
            variables: [],
            preview: {}
          },
          loading: false,
          error: false,
        },
        form: {
          processing: false,
        },
        job: {
          name: '',
          id: null,
        },
        templates: {
          show: `${window.location.origin}/wp-json/moovly/v1/templates/${this.id}`,
          save: `${window.location.origin}/wp-json/moovly/v1/templates/${this.id}/store`,
        },
      }
    },

    mounted()
    {
      this.fetch();
    },

    methods: {
      fetch()
      {
        this.ui.loading = true;
        axios.get(this.templates.show).then(response => {
          this.ui.template.name = response.data.name;
          this.ui.template.preview = response.data.preview;
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

      submit()
      {
        this.ui.loading = true;
        this.form.processing = true;
        let variableValues = this.ui.template.variables.map(variable => {
          return {
            [variable.id]: variable.value,
          }
        });
        axios.post(this.templates.save, {
          variables: variableValues,
          name: this.job.name,
        }).then(response => {
          this.ui.error = false;
          this.ui.loading = false;
          this.form.processing = false;
          this.job = {
            id: response.data.job_id,
          };
        }).catch(error => {
          this.ui.error = true;
          this.ui.error_message = error.response.data.message || '';
          this.ui.loading = false;
          this.form.processing = false;
        })
      }
    }
  }
</script>
