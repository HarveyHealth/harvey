<template>
  <div @click="emitClick" :class="{ overlay:true, isactive: isActive }"></div>
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
    this.$eventHub.$on('toggleOverlay', () => this.isActive = !this.isActive);
  }
}
</script>
