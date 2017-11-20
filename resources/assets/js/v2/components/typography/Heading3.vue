<template>
  <h3 :class="styles"><slot></slot></h3>
</template>

<script>
export default {
  props: {
    isLight: Boolean
  },
  computed: {
    styles() {
      return {
        'gray': !this.isLight,
        'Heading3': true,
        'f6': true,
        'fw6': true,
        'ma0': true,
        'pa0': true,
        'ttu': true,
        'tracked': true,
        'white': this.isLight
      };
    }
  }
};
</script>

<style lang="scss" scoped>
  @import '~sass';

  .Heading3 {
    @extend %font-smooth;
  }
</style>
