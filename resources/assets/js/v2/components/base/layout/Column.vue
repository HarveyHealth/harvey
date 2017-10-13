<template>
  <div :class="classes">
    <slot></slot>
  </div>
</template>

<script>
export default {
  // Grid column base is 12
  // config = { sm: '1of1', md: '1of2', lg: '1of4' }
  props: {
    config: Object
  },
  computed: {
    classes() {
      return Object.keys(this.config).map(key => `Column-${key}-${this.config[key]}`).join(' ');
    }
  }
}
</script>
