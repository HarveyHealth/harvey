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
        'dark-gray': !this.isLight,
        'Heading3': true,
        'f4': true,
        'fw6': true,
        'pa0': true,
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
    margin: 0;
  }
</style>
