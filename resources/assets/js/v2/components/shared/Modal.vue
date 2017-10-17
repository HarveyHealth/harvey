<template>
  <div v-if="active" class="Modal" :class="containerClass">
    <div class="Modal_Container">
      <button @click="onClose" class="Button--close Modal_CloseButton" v-if="!hideClose">
        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>
      </button>
      <div class="Modal_ScrollContent">
        <slot></slot>
      </div>
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
    // CSS classes for customization
    containerClass: {
      type: [String, Object]
    },
    hideClose: Boolean,
    // onClose should toggle the active prop state
    onClose: {
      type: Function,
      required: true
    }
  }
}
</script>
