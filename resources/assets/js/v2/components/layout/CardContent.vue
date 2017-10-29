<template>
  <div :class="styles">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    withBorder: Boolean
  },
  data() {
    return {
      styles: {
        'bg-white': true,
        'pa3': true,
        'bb b--light-gray': this.withBorder
      }
    }
  }
}
</script>
