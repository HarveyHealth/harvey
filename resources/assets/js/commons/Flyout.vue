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
      // Whether or not the flyout is open
      active: {
        type: Boolean,
        required: true,
      },
      // If the flyout contains a heading
      heading: {
        type: String,
      },
      // onClose should update the active prop
      onClose: {
        type: Function,
        required: true
      }
    }
  }
</script>
