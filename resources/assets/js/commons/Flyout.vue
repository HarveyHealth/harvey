<template>
  <aside :class="{ flyout: true, isactive: isActive }">
    <button class="button--close flyout-close" @click="closeFlyout()">
      <svg><use xlink:href="#close" /></svg>
    </button>

    <!-- TODO: Make this dynamic to the page... -->
    <h2 class="title">{{ heading }}</h2>
    <slot></slot>
  </aside>
</template>

<script>
  export default {
    data() {
      return {
        isActive: false,
        heading: '',
      }
    },
    methods: {
      closeFlyout() {
        this.$eventHub.$emit('callFlyout', true);
        this.$eventHub.$emit('deselectRows');
        if (this.$root.flyoutActive) this.$eventHub.$emit('toggleOverlay');
      }
    },
    mounted() {
      // activeDetails = are the details for this appointment already in view?
      // This is for toggling the flyout window if the same appointment is clicked
      this.$eventHub.$on('callFlyout', (activeDetails, heading) => {
        this.isActive = !activeDetails;
        this.heading = heading;
      });
    },
    destroyed() {
      this.$eventHub.$off('callFlyout');
    }
  }
</script>
