<template>
  <Flyout
    :active="$parent.detailFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
  >

  </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue'
import SelectOptions from '../../../commons/SelectOptions.vue'
export default {
  name: 'DetailLabOrders',
  components: {
    Flyout,
    SelectOptions
  },
  data() {
    return {
      flyoutHeading: "Lab Order Details"
    }
  },
  methods: {
    handleFlyoutClose() {
      this.$parent.detailFlyoutActive = !this.$parent.detailFlyoutActive
    }
  }
}
</script>
