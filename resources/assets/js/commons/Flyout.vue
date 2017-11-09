<template>
    <aside :class="{ flyout: true, isactive: active, 'copy-main': true, 'font-sm': true }">
            <button v-if="back" class="button--close flyout-close left" @click="back">
                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
            </button>
            <button v-if="onClose !== null" class="button--close flyout-close" @click="onClose" data-test="close">
                <svg><use xlink:href="#close" /></svg>
            </button>
            <h2 class="heading-3-expand" v-html="heading"></h2>
            <slot></slot>
    </aside>
</template>

<script>
  export default {
    props: {
      // Whether or not the flyout is open
      active: {
        type: Boolean,
        required: true
      },
      // If the flyout contains a heading
      heading: {
        type: String
      },
      // onClose should update the active prop
      onClose: {
        type: Function,
        required: false
      },
      button: {
        type: Boolean
      },
      header: {
        type: Boolean
      },
      headerTwo: {
        type: Boolean
      },
      back: {
        type: Function
      }
    }
  };
</script>
