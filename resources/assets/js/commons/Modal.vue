<template>
  <div v-if="active" class="modal-wrapper" :class="containerclass">
    <div class="modal">
      <button @click="handleExitClick()" class="button button--cancel modal-close">
        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>
      </button>
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: ['active', 'containerclass'],
  methods: {
    handleExitClick() {
      this.$eventHub.$emit('closeModal');
    }
  },
  mounted() {
  },
  destroyed() {
  }
}
</script>
