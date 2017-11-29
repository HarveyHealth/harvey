<template>
  <h3 :class="styles"><slot></slot></h3>
</template>

<script>
export default {
  props: {
    color: {
        type: String,
        default: 'dark'
    },
    size: {
        type: String,
        default: 'small'
    },
    isLight: Boolean
  },
  computed: {
    styles() {
      return {
        'dark-gray': this.color === 'dark',
        'gray': this.color === 'muted',
        'Heading3': true,
        'f4': this.size === 'large',
        'f6': this.size === 'small',
        'fw6': true,
        'pa0': true,
        'white': this.color === 'light'
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
