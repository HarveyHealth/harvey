<template>
  <h4 :class="styles"><slot></slot></h4>
</template>

<script>
export default {
  props: {
    isLight: Boolean,
  },
  computed: {
    styles() {
      return {
        'silver': !this.isLight,
        'Heading4': true,
        'f7': true,
        'fw6': true,
        'ma0': true,
        'pa0': true,
        'ttu': true,
        'tracked': true,
        'white': this.isLight
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  @import '~sass';

  .Heading4 {
    @extend %font-smooth;
  }
</style>
