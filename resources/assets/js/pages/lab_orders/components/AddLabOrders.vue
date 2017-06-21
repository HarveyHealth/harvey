<template>
  <Flyout
    :active="$parent.addFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
  >
    HELLO
  </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue'
export default {
  name: 'AddLabOrders',
  components: {
    Flyout
  },
  data() {
    return {
      flyoutHeading: "New Lab Order"
    }
  },
  methods: {
    handleFlyoutClose() {
      this.$parent.addFlyoutActive = !this.$parent.addFlyoutActive
    }
  }
}
</script>
