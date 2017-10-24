<template>
  <div :class="classes">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    align: String,
    gutter: String
  },
  computed: {
    classes() {
      return {
        Row: true,
        [`align-${this.align}`]: this.align,
        [`gutter-${this.gutter}`]: this.gutter
      };
    }
  }
};
</script>
