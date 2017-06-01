<template>
  <div @click="emitClick" :class="{ overlay:true, isactive: active }"></div>
</template>

<script>
export default {
  props: ['active'],
  methods: {
    emitClick() {
      this.$eventHub.$emit('overlayClicked');
    }
  },
}
</script>
