<template>
  <aside :class="{ flyout: true, isactive: active }">
    <button class="button--close flyout-close" @click="closeFlyout()">
      <svg><use xlink:href="#close" /></svg>
    </button>
    <h2 class="title">{{ heading }}</h2>
    <slot></slot>
  </aside>
</template>

<script>
  export default {
    props: ['active', 'heading'],
    methods: {
      closeFlyout() {
        this.$eventHub.$emit('closeFlyout');
      }
    },
  }
</script>
