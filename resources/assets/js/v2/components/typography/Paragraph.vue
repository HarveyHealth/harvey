<template>
  <p :class="styles"><slot></slot></p>
</template>

<script>
export default {
  props: {
    isBold: Boolean,
    isThin: Boolean,
    isLight: Boolean,
    isError: Boolean
  },
  computed: {
    styles() {
      return {
        'ma0': true,
        'fw2': this.isThin || !this.isBold, // default
        'fw6': this.isBold,
        'white': this.isLight,
        'dark-gray': !this.isLight // default
      };
    }
  }
};
</script>
