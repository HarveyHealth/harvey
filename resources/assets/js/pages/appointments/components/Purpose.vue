<template>
  <div class="input__container">
    <label class="input__label" for="purpose">purpose</label>
    <span v-if="editable" :class="{ charcount:true, 'input--warning':count === 0 }">{{ count }}</span>
    <p v-if="!editable" class="input__item">{{ textValue }}</p>
    <textarea
      v-else
      class="input--textarea"
      v-model="textValue"
      @input="charCheck()"
    ></textarea>
  </div>
</template>

<script>
export default {
  props: ['editable'],
  data() {
    return {
      charLimit: 180,
      classNames: { 'input__container': true },
      textValue: ''
    }
  },
  computed: {
    count() {
      return this.charLimit - this.textValue.length;
    },
    countIsZero() {
      return (this.charLimit - this.textValue.length) < 0;
    },
  },
  methods: {
    charCheck() {
      if (this.countIsZero) {
        this.textValue = this.textValue.substring(0, this.charLimit);
      }
      this.$eventHub.$emit('setPurpose', this.textValue);
    }
  },
  mounted() {
    this.$eventHub.$on('forcePurposeText', text => this.textValue = text);
  },
  destroyed() {
    this.$eventHub.$off('forcePurposeText');
  }
}
</script>
