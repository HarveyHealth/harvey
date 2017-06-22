<template>
  <Flyout
    :active="$parent.detailFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
  >
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">lab tests</label>
              <label class="input__label" style="color: #737373;">{{ doctorName }} <a style="color: #B4E7A0;">(Track Cli)</a></label>
          </div>
        </div>
        <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">doctor</label>
              <label class="input__label" style="color: #737373;">{{ doctorName }}</label>
          </div>
        </div>
      </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">shipping address</label>
              <label class="input__label" style="color: #737373;">{{ addressOne }}</label>
              <label class="input__label" style="color: #737373;">{{ addressTwo }}</label>
          </div>
        </div>
      </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">order tracking</label>
              <label class="input__label" style="color: #737373;">{{ shipmentCode }}</label>
          </div>
        </div>
      </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">order status</label>
              <label class="input__label" style="color: #737373;">{{ status }}</label>
          </div>
        </div>
      </div>
  </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue'
import SelectOptions from '../../../commons/SelectOptions.vue'
export default {
  name: 'DetailLabOrders',
  props: ['row-data'],
  components: {
    Flyout,
    SelectOptions
  },
  data() {
    return {
    }
  },
  methods: {
    handleFlyoutClose() {
      this.$parent.detailFlyoutActive = !this.$parent.detailFlyoutActive
    }
  },
  computed: {
    flyoutHeading() {
      return `Lab Order #${this.$props.rowData.id}`
    },
    doctorName() {
      return `Dr. ${this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.name}`
    },
    status() {
      return this.$props.rowData.completed_at
    },
    shipmentCode() {
      return this.$props.rowData.shipment_code
    },
    addressOne() {
      return this.$props.rowData.address_1 || ''
    },
    addressTwo() {
      return this.$props.rowData.address_2 || ''
    }
  }
}
</script>
