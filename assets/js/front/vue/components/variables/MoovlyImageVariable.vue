<template>
  <moovly-file-variable
      v-model="value"
      @input="input"
  >
        <span slot="requirements">
            Image dimensions: {{ variable.requirements.height}} x {{ variable.requirements.width }}
        </span>
    <img slot="preview" v-if="variable.value" :src="variable.object.assets[0].source" class="img-fluid">
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
      }
    }
  }
</script>
