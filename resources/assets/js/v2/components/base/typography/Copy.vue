<template>
  <p :class="type"><slot></slot></p>
</template>

<script>
export default {
  props: {
    // "default" or "muted"
    type: {
      type: String,
      default: 'default'
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~sass';

.default {
  @extend %copy--default;
}
</style>
