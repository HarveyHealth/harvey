<template>
  <svg :style="{ width: this.width, height: this.height }">
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
      type: String,
      default: '100%'
    },
  }
}
</script>
