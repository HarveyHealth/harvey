<template>
  <div :style="style">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    to: {
      type: String,
      default: 'top'
    }
  },
  data() {
    return {
      style: {
        'opacity': '0',
        'position': 'relative',
        [`${this.to}`]: '16px',
        'transition': `${this.to} 300ms ease-in-out, opacity 200ms ease-in-out`,
      }
    }
  },
  mounted() {
    setTimeout(() => {
      this.style.opacity = '1';
      this.style[this.to] = '0';
    }, 100)
  }
}
</script>
