<template>
  <h1 :class="styles"><slot></slot></h1>
</template>

<script>
export default {
  props: {
    doesExpand: Boolean,
    // light, dark, muted
    color: {
        type: String,
        default: 'dark'
    },
    // thin, normal, bold
    weight: {
        type: String,
        default: 'normal'
    }
  },
  computed: {
    styles() {
      return {
        'dark-gray': this.color === 'dark',
        'Heading1': true,
        'f3': this.doesExpand,
        'f2': !this.doesExpand,
        'f2-m': this.doesExpand,
        'fw2': this.weight === 'thin',
        'fw4': this.weight === 'normal',
        'fw6': this.weight === 'bold',
        'lh-title': true,
        'white': this.color === 'light'
      };
    }
  }
};
</script>

<style lang="scss" scoped>
  @import '~sass';

  .Heading1 {
    @extend %font-smooth;
    margin: 0;
  }
</style>
