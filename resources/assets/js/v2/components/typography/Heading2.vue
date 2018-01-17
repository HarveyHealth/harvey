<template>
  <h2 :class="styles"><slot></slot></h2>
</template>

<script>
export default {
  props: {
    doesExpand: Boolean,
    isLight: Boolean
  },
  computed: {
    styles() {
      return {
        'dark-gray': !this.isLight,
        'Heading2': true,
        'f4': this.doesExpand,
        'f3': !this.doesExpand,
        'f3-m': this.doesExpand,
        'fw3': true,
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
