<template>
  <p :class="styles"><slot></slot></p>
</template>

<script>
export default {
  props: {
    isBold: Boolean,
    isThin: Boolean,
    isLight: Boolean,
    isError: Boolean,
    isLarge: Boolean
  },
  computed: {
    styles() {
      return {
        'ma0': true,
        'font-lg': this.isLarge,
        'fw4': !this.isThin && !this.isBold,
        'fw3': this.isThin,
        'fw5': this.isBold,
        'white': this.isLight,
        'dark-gray': !this.isLight
      };
    }
  }
};
</script>
