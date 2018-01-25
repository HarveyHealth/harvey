<template>
  <h2 :class="styles"><slot></slot></h2>
</template>

<script>
export default {
  props: {
    doesExpand: Boolean,
    isLight: Boolean,
    size: Number
  },
  computed: {
    styles() {
      return {
        'dark-gray': !this.isLight,
        'Heading2': true,
        [`f${this.size}`]: this.size,
        'f4': this.doesExpand && !this.size,
        'f3': !this.doesExpand && !this.size,
        'f3-m': this.doesExpand && !this.size,
        'fw4': true,
        'pa0': true,
        'white': this.isLight
      };
    }
  }
};
</script>

<style lang="scss" scoped>
  @import '~sass';

  .Heading2 {
      margin: 0;
  }
</style>
