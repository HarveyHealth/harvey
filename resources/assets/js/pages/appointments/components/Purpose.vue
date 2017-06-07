<template>
  <div class="input__container">
    <label class="input__label" for="purpose">purpose</label>
    <span v-if="editable" :class="$$countClasses">{{ $$count }}</span>
    <p v-if="!editable" class="input__item">{{ textValue }}</p>
    <textarea
      v-else
      class="input--textarea"
      :value="textValue"
      @input="$$count > 0 ? onInput($event.target.value) : false"
    ></textarea>
  </div>
</template>

<script>
export default {
  props: {
    characterLimit: {
      type: Number,
      required: true
    },
    editable: {
      type: Boolean,
      default: true
    },
    onInput: {
      type: Function,
      required: true
    },
    textValue: {
      type: String,
      required: true
    }
  },
  computed: {
    $$count() {
      return this.characterLimit - this.textValue.length;
    },
    $$countClasses() {
      return {
        'charcount': true,
        'input--warning': this.$$count === 0
      }
    }
  }
}
</script>
