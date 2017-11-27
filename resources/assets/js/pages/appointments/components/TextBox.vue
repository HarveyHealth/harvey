<template>
  <div class="input__container" v-if="visible">
    <label class="input__label" :for="name">{{ name }}</label>
    <span v-if="editable" :class="$$countClasses">{{ $$count }}</span>
    <p v-if="!editable" class="input__item">{{ value }}</p>
    <textarea v-else
      class="input--textarea small"
      :value="value"
      @input="updateValue($event.target.value)"
      :maxlength="characterLimit"
    ></textarea>
  </div>
</template>

<script>
export default {
  props: {
    characterLimit: {
      type: Number,
      required: false,
      default: 256
    },
    editable: {
      type: Boolean,
      default: false
    },
    visible: {
      type: Boolean,
      default: false
    },
    name: {
      type: String,
      required: true
    },
    value: {}
  },
  computed: {
    $$count() {
      return this.characterLimit - this.value.length;
    },
    $$countClasses() {
      return {
        'charcount': true,
        'input--warning': this.$$count <= 0
      };
    }
  },
  methods: {
    updateValue: function (value) {
      this.$emit('input', value.substring(0, this.characterLimit));
    }
  }
};
</script>
