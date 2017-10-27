<template>
  <span :class="styles"></span>
</template>

<script>
export default {
  props: {
    isRight: Boolean,
    isLeft: Boolean,
    isTop: Boolean,
    isBottom: Boolean,
    size: Number
  },
  computed: {
    styles() {
      return {
        'db': this.isTop || this.isBottom,
        'dib': this.isRight || this.isLeft,
        [`mr${this.size}`]: this.isRight,
        [`ml${this.size}`]: this.isLeft,
        [`mt${this.size}`]: this.isTop,
        [`mb${this.size}`]: this.isBottom,
      }
    }
  }
}
</script>
