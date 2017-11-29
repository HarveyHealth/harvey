<template>
    <div :class="style"></div>
</template>

<script>
export default {
  name: 'background',
  props: {
      color: {
          type: String,
          required: true
      }
  },
  computed: {
      style() {
          return `bg is-${this.color}`;
      }
  }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .bg {
        background-color: $color-white;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: -1;

        &.is-brown      { background-image: $bg-fade-brown; }
        &.is-ford       { background-image: $bg-fade-ford; }
        &.is-green      { background-image: $bg-fade-green; }
        &.is-lime       { background-image: $bg-fade-lime; }
        &.is-pink       { background-image: $bg-fade-pink; }
        &.is-purple     { background-image: $bg-fade-purple; }
        &.is-slate      { background-image: $bg-fade-slate; }
        &.is-turquoise  { background-image: $bg-fade-turquoise; }
    }
</style>
