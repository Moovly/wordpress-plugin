<template>
  <div class="form-group">
    <label :for="variable.id"> {{ variable.label}}</label>
    <template v-if="!ui.object">
      <input
          type="file"
          :accept="accept"
          class="form-control-file"
          @change="setObject"
          required
          :disabled="disabled"
          v-if="!ui.object"
      >
      <small class="form-text text-muted">
        <slot name="requirements"></slot>
      </small>
    </template>
    <div v-else>
      <slot name="preview"></slot>
      <button type="button" @click="resetObject" class="btn">Reset {{ variable.type }}</button>
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

    data()
    {
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

    created()
    {
      this.setValue();
    },

    methods: {
      setValue()
      {
        this.variable = this.value;
      },

      setObject(event)
      {
        const file = event.target.files[0];
        const formData = new FormData();
        formData.append('file', file);
        this.$emit('change', file);

        this.ui.loading = true;
        this.ui.file = {
          name: file.name,
        };

        axios.post(`${window.location.origin}/wp-json/moovly/v1/objects/upload-${this.variable.type}`, formData, {
          headers: {'Content-Type': 'multipart/form-data'},
        }).then(response => {
          this.ui.object = response.data;
          this.variable.value = this.ui.object.id;
          this.variable.object = this.ui.object;
          this.ui.loading = false;
          this.input();
        }).catch(error => {
          this.ui.error = true;
        });
      },

      resetObject()
      {
        this.ui.file = null;
        this.ui.object = null;
        this.variable.value = null;
        this.input();
      },

      input()
      {
        this.$emit('input', this.variable);
      }
    }
  }
</script>
