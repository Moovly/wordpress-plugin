<template>
  <moovly-file-variable
      v-model="value"
      @input="input"
      @change="change"
  >
    <span slot="requirements">
      Video dimensions: {{ variable.requirements.height}} x {{ variable.requirements.width }}
      <br>
      <template v-if="variable.requirements.minimum_duration === variable.requirements.maximum_duration">
        Duration must be {{ variable.requirements.minimum_duration }} seconds
      </template>
      <template v-else>
        Duration must be between {{ variable.requirements.minimum_duration }} seconds and  {{ variable.requirements.maximum_duration }} seconds
      </template>
    </span>
    <div slot="preview" v-if="variable.value && file" class="embed-responsive embed-responsive-16by9">
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

    created()
    {
      this.setValue();
    },

    data()
    {
      return {
        variable: null,
        file: null,
      }
    },

    methods: {
      setValue()
      {
        this.variable = this.value;
      },

      input()
      {
        this.$emit('input', this.variable);
      },

      change(file)
      {
        file.src = URL.createObjectURL(file);
        this.file = file;
      },
    }
  }
</script>
