<template>
  <h1 :class="styles"><slot></slot></h1>
</template>

<script>
export default {
  props: {
    doesExpand: Boolean,
    isBold: Boolean,
    isLight: Boolean,
    isThin: Boolean
  },
  computed: {
    styles() {
      return {
        'dark-gray': !this.isLight,
        'Heading1': true,
        'f3': this.doesExpand,
        'f2': !this.doesExpand,
        'f2-m': this.doesExpand,
        'fw2': this.isThin,
        'fw4': !this.isBold && !this.isThin,
        'fw6': this.isBold,
        'lh-title': true,
        'ma0': true,
        'pa0': true,
        'white': this.isLight
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  @import '~sass';

  .Heading1 {
    @extend %font-smooth;
  }
</style>
