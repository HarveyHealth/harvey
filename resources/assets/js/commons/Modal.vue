<template>
  <div v-if="active" class="modal-wrapper" :class="containerClass">
    <div class="modal">
      <button @click="onClose" class="button button--cancel modal-close" v-if="!hideClose">
        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>
      </button>
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
    props: {
    // Whether the modal is open or not
    active: {
      type: Boolean,
      required: true
    },
    hideClose: Boolean,
    // CSS classes for customization
    containerClass: {
      type: [String, Object]
    },
    // onClose should toggle the active prop state
    onClose: {
      type: Function,
      required: true
    }
  }
};
</script>
