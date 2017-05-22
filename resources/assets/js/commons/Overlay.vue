<template>
  <div @click="emitClick" :class="{ overlay:true, isactive: $root.flyoutActive }"></div>
</template>

<script>
export default {
  data() {
    return {
      isActive: false
    }
  },
  methods: {
    emitClick() {
      this.$eventHub.$emit('overlayClicked');
    }
  },
  mounted() {
    this.$eventHub.$on('toggleOverlay', () => this.$root.flyoutActive = !this.$root.flyoutActive);
  },
  destroyed() {
    this.$eventHub.$off('toggleOverlay');
  }
}
</script>
