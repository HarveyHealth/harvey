<template>
  <svg :style="styles">
    <use :xlink:href="'#' + id" />
  </svg>
</template>

<script>
export default {
  props: {
    height: {
      type: String,
      default: '',
    },
    id: String,
    width: {
      type: String
    },
  },
  computed: {
    styles() {
      return {
        width: this.width,
        height: this.height
      }
    }
  }
}
</script>
