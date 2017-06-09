<template>
  <aside :class="{ flyout: true, isactive: active }">
    <button class="button--close flyout-close" @click="onClose">
      <svg><use xlink:href="#close" /></svg>
    </button>
    <h2 class="title" v-html="heading"></h2>
    <slot></slot>
  </aside>
</template>

<script>
  export default {
    props: {
      active: {
        type: Boolean,
        required: true,
      },
      heading: {
        type: String,
      },
      onClose: {
        type: Function,
        required: true
      }
    }
  }
</script>
