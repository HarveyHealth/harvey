<template>
  <div :class="{ 'overlay': true, `overlay--${overlay_theme}`: theme, 'isactive': isActive }"></div>
</template>

<script>
  export default {
    data() {
      return {
        isActive: false,
        overlay_theme: this.theme || 'dark'
      }
    },
    prop: ['theme'],
    mounted() {
      this.$eventHub.$on('toggleOverlay', state => {
        this.isActive = state;
      });
    }
  }
</script>
