<template>
  <Flyout
    :active="$parent.addFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
  >

  </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue'
import SelectOptions from '../../../commons/SelectOptions.vue'
export default {
  name: 'AddLabOrders',
  components: {
    Flyout,
    SelectOptions
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
