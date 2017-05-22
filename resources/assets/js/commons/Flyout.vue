<template>
  <aside :class="{ flyout: true, isactive: isActive }">
    <!-- TODO: Make this dynamic to the page... -->
    <h2 class="title">New Appointment</h2>
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
    },
    destroyed() {
      this.$eventHub.$off('callFlyout');
    }
  }
</script>
