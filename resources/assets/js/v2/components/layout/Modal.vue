<template>
  <div v-if="active" :class="{ Modal: true, 'is-simple': isSimple }">
    <div class="Modal_Container">
      <SlideIn class="Modal_Wrap">
        <button @click="onClose" class="Button--close Modal_CloseButton" v-if="!hideClose">
          <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>
        </button>
        <div class="Modal_ScrollContent">
          <slot name="content"></slot>
        </div>
        <div class="Modal_Footer">
          <slot name="footer"></slot>
        </div>
      </SlideIn>
    </div>
  </div>
</template>

<script>
import SlideIn from './SlideIn.vue';

export default {
  components: {
    SlideIn
  },
  props: {
    // Whether the modal is open or not
    active: {
      type: Boolean,
      required: true
    },
    hideClose: Boolean,
    // isSimple will remove height fixing
    isSimple: Boolean,
    // onClose should toggle the active prop state
    onClose: {
      type: Function,
      required: true
    }
  }
}
</script>
