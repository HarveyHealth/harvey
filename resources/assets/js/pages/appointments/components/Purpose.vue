<template>
  <div class="input__container" v-if="isVisible">
    <label class="input__label" for="purpose">purpose</label>
    <span v-if="editable" :class="$$countClasses">{{ $$count }}</span>
    <p v-if="!editable" class="input__item">{{ textValue }}</p>
    <textarea
      v-else
      class="input--textarea small"
      :value="textValue"
      @input="$$count > -1 ? onInput($event) : false"
    ></textarea>
  </div>
</template>

<script>
export default {
  props: {
    // Limits the number of characters the user can input
    characterLimit: {
      type: Number,
      required: true
    },
    // Is the purpose editable or display only?
    editable: {
      type: Boolean,
      default: true
    },
    // What happens when a user inputs something into the textarea?
    // Function takes the textarea value if not empty.
    // This should probably update the textValue prop in immitation of v-model.
    onInput: {
      type: Function,
      required: true
    },
    // The initial value of the textarea or display.
    textValue: {
      type: String,
      required: true
    },
    isVisible: Boolean
  },
  computed: {
    // Checking if the value is over the character limit
    $$count() {
      return this.characterLimit - this.textValue.length;
    },
    // Add class if character limit has been reaced.
    $$countClasses() {
      return {
        'charcount': true,
        'input--warning': this.$$count === 0
      };
    }
  }
};
</script>
