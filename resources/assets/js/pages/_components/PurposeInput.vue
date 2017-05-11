<template>
  <div :class="classNames">
    <label class="input__label" for="purpose">purpose</label>
    <span :class="{ charcount:true, iszero:count === 0 }">{{ count }}</span>
    <textarea
      class="input--textarea"
      v-model="textValue"
      @input="charCheck()"
    ></textarea>
  </div>
</template>

<script>
export default {
  props: ['classes', 'purposetext', 'type', 'usertype'],
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
    }
  },
  methods: {
    charCheck() {
      if (this.countIsZero) {
        this.textValue = this.textValue.substring(0, this.charLimit);
      }
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
  mounted() {
    this.$eventHub.$on('setPurposeText', () => this.textValue = this.purposetext);
  }
}
</script>
