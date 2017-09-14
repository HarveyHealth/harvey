<template>
  <div :class="classes">
    <slot></slot>
  </div>
</template>

<script>
export default {
  computed: {
    classes() {
      return {
        Row: true,
        [`align-${this.align}`]: this.align,
        [`gutter-${this.gutter}`]: this.gutter
      };
    }
  },
  props: {
    align: String,
    gutter: String
  }
}
</script>
