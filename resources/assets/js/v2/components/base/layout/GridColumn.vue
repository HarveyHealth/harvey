<template>
  <div :class="classes">
    <slot></slot>
  </div>
</template>

<script>
export default {
  computed: {
    classes() {
      return Object.keys(this.config).map(bp => {
        return `Column-${bp}-${this.config[bp]}`;
      }).join(' ');
    }
  },
  props: {
    config: Object,
    required: true
  }
}
</script>
