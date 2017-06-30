<template>
  <Flyout
    :active="$parent.detailFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
  >
    <div v-if="$root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type !== 'admin'">
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">lab tests</label>
              <label v-for="test in testList" class="input__label" style="color: #737373;">{{ test.name }} <a v-if="!test.cancel" style="color: #B4E7A0;">(Track Cli)</a></label>
          </div>
        </div>
        <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">doctor</label>
              <label class="input__label" style="color: #737373;">{{ doctorName }}</label>
          </div>
        </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">shipping address</label>
              <label class="input__label" style="color: #737373;">{{ addressOne }}</label>
              <label class="input__label" style="color: #737373;">{{ addressTwo }}</label>
          </div>
        </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">order tracking</label>
              <label class="input__label" style="color: #737373;">{{ shipmentCode }}</label>
          </div>
        </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">order status</label>
              <label class="input__label" style="color: #737373;">{{ status }}</label>
          </div>
        </div>
      </div>
    </div>
  <div v-if="$root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'admin'">
        <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
            <div class="input__container">
                <label class="input__label" for="patient_name">lab tests</label>
                <div v-for="test in testList">
                  <label class="input__label" style="color: #737373;">{{ test.name }}</label>
                  <span class="custom-select">
                      <select @change="updateTest($event, test)">
                          <option v-for="current in test.status">{{ current }}</option>
                      </select>
                  </span>
                </div>
            </div>
          </div>
          <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
            <div class="input__container">
                <label class="input__label" for="patient_name">doctor</label>
                <span class="input--text">{{ doctorName }}</span>
            </div>
          </div>
        <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
            <div class="input__container">
                <label class="input__label" for="patient_name">shipping address</label>
                <label class="input__label" style="color: #737373;">{{ addressOne }}</label>
                <label class="input__label" style="color: #737373;">{{ addressTwo }}</label>
                <label class="input__label" style="color: #737373;">{{ city }}</label>
                <label class="input__label" style="color: #737373; width: 45%; float:left; margin-right: 5%;">{{ state }}</label>
                <label class="input__label" style="color: #737373; width: 50%; float: left;">{{ zip }}</label>
            </div>
          </div>
        <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
            <div class="input__container">
                <label class="input__label" for="patient_name">order tracking</label>
                <span class="input--text">{{ shipmentCode }}</span>
            </div>
          </div>
        <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
            <div class="input__container">
          <label class="input__label" for="patient_name">order status</label>
          <span class="input--text">{{ status }}</span>
      </div>
          </div>
          <div>
            <div class="inline-centered">
                <button class="button"
                @click="updateOrder()"
                >Update Shipment</button>
            </div>
        </div>
        </div>
      </div>

  </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue'
import SelectOptions from '../../../commons/SelectOptions.vue'
import {capitalize} from '../../../utils/filters/textformat'
import axios from 'axios'
export default {
  name: 'DetailLabOrders',
  props: ['row-data'],
  components: {
    Flyout,
    SelectOptions
  },
  data() {
    return {
      selectedStatus: null,
      selectedDoctor: null,
      selectedShipment: {},
      selectedAddressOne: null,
      selectedAddressTwo: null
    }
  },
  methods: {
    handleFlyoutClose() {
      this.$parent.detailFlyoutActive = !this.$parent.detailFlyoutActive
    },
    updateStatus(e) {
        this.selectedStatus = e.target.value;
    },
    updateTest(e, object) {
      this.selectedShipment[object.test_id] = e.target.value;
    },
    updateOrder() {
      this.$props.rowData.test_list.forEach(e => {
        if (this.selectedShipment[Number(e.test_id)] != undefined) {
          axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
            status: this.selectedShipment[Number(e.test_id)].toLowerCase()
          })
        }
      })
      this.$parent.notificationMessage = "Successfully updated!";
      this.$parent.notificationActive = true;
      setTimeout(() => this.$parent.notificationActive = false, 3000);
      this.handleFlyoutClose()
    }
  },
  computed: {
    flyoutHeading() {
      return this.$props.rowData ? `Lab Order #${this.$props.rowData.id}` : ''
    },
    doctorName() {
      return this.$props.rowData ? `Dr. ${this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.name}` : ''
    },
    status() {
      return this.$props.rowData ? this.$props.rowData.completed_at : ''
    },
    shipmentCode() {
      return this.$props.rowData ? this.$props.rowData.shipment_code : ''
    },
    addressOne() {
      return this.$props.rowData ? this.$props.rowData.address_1 : ''
    },
    addressTwo() {
      return this.$props.rowData ? this.$props.rowData.address_2 : ''
    },
    city() {
      return this.$props.rowData ? this.$props.rowData.city : ''
    },
    state() {
      return this.$props.rowData ? this.$props.rowData.state : ''
    },
    zip() {
      return this.$props.rowData ? this.$props.rowData.zip : ''
    },
    doctorList() {
      let data = {}
      if (!this.$props.rowData) return []
      data.name = `Dr. ${this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.name}`
      data.id = this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].id
      data.user_id = this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.user_id
      let arr = _.pull(this.$root.$data.global.practitioners, data)
      return [data].concat(arr)
    },
    statusList() {
      if (!this.$props.rowData) return ""
      return this.$props.rowData.completed_at
    },
    testList() {
      if (!this.$props.rowData) return []
      this.$props.rowData.test_list = this.$props.rowData && this.$props.rowData.test_list.length == 0 ? [{name: "No Lab Orders", cancel: true, status: ['No Order']}] : this.$props.rowData.test_list
      return this.$props.rowData.test_list
    }
  }
}
</script>
