<template>
  <div v-if="active" class="modal-wrapper" :class="containerClass">
    <div class="modal">
      <button @click="onClose" class="button button--cancel modal-close">
        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>
      </button>
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    active: {
      type: Boolean,
      required: true
    },
    containerClass: {
      type: [String, Object]
    },
    onClose: {
      type: Function,
      required: true
    }
  }
}
</script>
