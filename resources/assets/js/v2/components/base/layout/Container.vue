<template>
  <div class="main-container">
    <div :class="classes">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    classes() {
      return {
        'main-content': this.type === 'main'
      }
    }
  },
  props: {
    type: {
      type: String,
      default: 'main'
    }
  }
}
</script>
