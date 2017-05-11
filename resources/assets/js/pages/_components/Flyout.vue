<template>
  <aside :class="{ flyout: true, isactive: isActive }">
    <slot></slot>
  </aside>
</template>

<script>
  export default {
    data() {
      return {
        isActive: false
      }
    },
    mounted() {
      // activeDetails = are the details for this appointment already in view?
      // This is for toggling the flyout window if the same appointment is clicked
      this.$eventHub.$on('callFlyout', activeDetails => {
        this.isActive = !activeDetails;
      });
    }
  }
</script>
