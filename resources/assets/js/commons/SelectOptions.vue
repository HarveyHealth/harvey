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
    attachedLabel: {
      type: String,
    },
    detachedLabel: {
      type: String,
    },
    isDisabled: {
      type: Boolean
    },
    isLoading: {
      type: Boolean
    },
    isRequired: {
      type: Boolean,
      default: true
    },
    loadingMsg: {
      type: String,
      default: 'Loading...'
    },
    onSelect: {
      type: Function,
      required: true,
      default: () => console.warn('Must pass on-select handler')
    },
    options: {
      type: Array,
      required: true
    },
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
      }
    }
  }
}
</script>
