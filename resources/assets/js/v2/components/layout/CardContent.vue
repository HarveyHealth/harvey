<template>
  <div :class="styles">
    <slot></slot>
  </div>
</template>

<script>
// CardContent
// ----------------------------------------------------------------------------
// CardContent is just a container with white background and padding that
// mirrors the Card component. The withBorder prop tells the component to have
// a bottom border if needed (to create stacked CardContent slots)
export default {
  props: {
    withBorder: Boolean
  },
  data() {
    return {
      styles: {
        'pa3': true,
        'bb b--light-gray': this.withBorder
      }
    };
  }
};
</script>
