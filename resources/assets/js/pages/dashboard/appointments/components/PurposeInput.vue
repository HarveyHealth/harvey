<template>
  <div :class="classNames">
    <label class="input__label" for="purpose">purpose</label>
    <span v-if="!past && !displayOnly" :class="{ charcount:true, 'input--warning':count === 0 }">{{ count }}</span>
    <p v-if="past || displayOnly" class="input__item">{{ textValue }}</p>
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
  props: ['classes', 'past', 'purposetext', 'status', 'type', 'usertype'],
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
    displayOnly() {
      return this.status !== 'pending' && Laravel.user.userType === 'patient';
    }
  },
  methods: {
    charCheck() {
      if (this.countIsZero) {
        this.textValue = this.textValue.substring(0, this.charLimit);
      }
      this.$eventHub.$emit('updatePurpose', this.textValue);
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
  mounted() {
    this.$eventHub.$on('setPurposeText', () => this.textValue = this.purposetext);
  },
  destroyed() {
    this.$eventHub.$off('setPurposeText');
  }
}
</script>
