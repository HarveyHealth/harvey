<template>
  <div>
    <span v-if="isLoading" v-html="loadingMsg"></span>
    <span v-else :class="$$selectionClasses" :data-detached-label="detachedLabel">
      <select :value="selected" @change="onSelect($event)" :disabled="isDisabled">
        <option v-if="$$hasAttachedLabel" disabled value="">{{ attachedLabel }}</option>
        <option v-if="!isRequired"></option>
        <option v-for="item in options">{{ item.value }}</option>
      </select>
    </span>
  </div>
</template>

<script>
export default {
  props: {
    // Attached labels are disabled options that act as an Initial
    // value as well as a prompt to the user. This is typically used
    // if the selection is required for a form submission
    attachedLabel: {
      type: String
    },
    // Detached Labels use CSS pseudo selectors and the data-detached-label
    // attribute to display a label over top an empty selection input.
    // This option is typically used if you want to give the user a blank
    // option because the selection is not required for form submission
    detachedLabel: {
      type: String
    },
    // Whether the entire selection input is diabled or not
    isDisabled: {
      type: Boolean
    },
    // If the selection options depend on Promised data, this prop
    // indicates if the data is still loading or not
    isLoading: {
      type: Boolean
    },
    // If the selection is not required for form submission, an empty
    // option will render at the beginning of the list
    isRequired: {
      type: Boolean,
      default: true
    },
    // If the selection has an isLoading state of true, this message will
    // display in place of the selection input
    loadingMsg: {
      type: String,
      default: 'Loading...'
    },
    // Handles what happens when an option is selected from the list.
    // The function takes the click event as an argument.
    onSelect: {
      type: Function,
      required: true,
      default: () => console.warn('Must pass on-select handler')
    },
    // The list of options with associated data
    // [
    //   {
    //     value: 'Option',
    //     data: { id: 1, name: 'some-option' }
    //   },
    //  ...
    // ]
    options: {
      type: Array,
      required: true
    },
    // Using this to recreate a v-model scenario. This way we can render the options
    // with a particular option already selected (useful for pulling up saved state)
    selected: {
      type: String,
      required: true
    }
  },
  // Computed properties prefixed with $$ reference props only so they're
  // pretty much stateless. I just didn't want to include this logic in
  // the template string
  computed: {
    $$hasAttachedLabel() {
      return this.isRequired && this.attachedLabel;
    },
    $$selectionClasses() {
      return {
        'custom-select': true,
        'detached-label': this.detachedLabel && this.selected === '',
        'isdisabled': this.isDisabled
      };
    }
  }
};
</script>
