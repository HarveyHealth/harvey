<template>
  <aside :class="{ flyout: true, isactive: active, 'copy-main': true, 'font-sm': true }">
    <button v-if="back" style="float: left; right: 0; left: 1em; color: #999999;" class="button--close flyout-close" @click="back">
      <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
    </button>
    <button v-if="!button" class="button--close flyout-close" @click="onClose">
      <svg><use xlink:href="#close" /></svg>
    </button>
    <h2 v-html="heading" :style="!header ? '' : 'float: left; margin-right: 50px;'"></h2>
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
      },
      button: {
        type: Boolean
      },
      header: {
        type: Boolean
      },
      back: {
        type: Function
      }
    }
  }
</script>
