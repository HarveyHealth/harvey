<template>
  <SlideIn v-if="isLoading" class="Card pad-sm space-children-xs max-width-md margin-0a">
    <Loading :size="'md'" :color="'muted'" />
    <p class="copy-muted font-italic font-centered font-sm" v-html="message"></p>
  </SlideIn>
</template>

<script>
import Loading from './Loading';
import SlideIn from './SlideIn';

export default {
  components: { Loading, SlideIn },
  props: {
    isLoading: {
      type: Boolean,
      required: true
    },
    message: {
      type: String,
      required: true
    }
  }
}
</script>
