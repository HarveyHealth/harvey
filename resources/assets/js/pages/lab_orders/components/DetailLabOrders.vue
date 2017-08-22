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
          <div v-for="val in samples" class="input__container">
              <label class="input__label" for="patient_name">{{ val }}</label>
              <label class="input__label" style="color: #737373;">Required</label>
          </div>
      </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">shipping address</label>
              <label class="input__label" style="color: #737373;">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</label>
              <label class="input__label" style="color: #737373;">{{ city }}, {{ state }} {{ zip }}</label>
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
              <label class="input__label" for="patient_name">billing info</label>
              <div v-if="status !== 'Recommended'">
                <label class="input__label" style="color: #737373;">{{`Billed to: ${card.brand} ****${card.last4}`}}</label>
                <label class="input__label" style="color: #737373;">{{`Charged: $${price}`}}</label>
              </div>
              <div v-if="status === 'Recommended' && $root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'practitioner'">
                <label class="input__label" style="color: #737373;">not paid yet</label>
              </div>
             <div v-if="status === 'Recommended' && $root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'patient'">
               <div v-if="card.last4 && card.brand">
                  <label class="input__label" style="color: #737373;">{{`Billed to: ${card.brand} ****${card.last4}`}}</label>
                  <label class="input__label" style="color: #737373;">{{`Charged: $${price}`}}</label>
                </div>
                <div v-if="!card.last4 && !card.brand" style="padding-top: 5px;">
                  <div class="input__container length" style="margin-bottom: 1.5em; font-size: 0.9em;">
                      <label class="input__label" for="patient_name">card number</label>
                      <input placeholder="Enter card number" v-model="cardNumber" class="input--text" type="text">
                  </div>
                  <div class="input__container length" style="font-size: 0.9em;">
                      <label class="input__label" for="patient_name">name on card</label>
                      <input placeholder="First name" style="width: 48%; float: left;" v-model="firstName" class="input--text" type="text">
                      <input placeholder="Last name" style="width: 48%; float: right;" v-model="lastName" class="input--text" type="text">
                  </div>
                  <div class="input__container length" style="padding-top: 25px; font-size: 0.9em;">
                      <label class="input__label" for="patient_name">expiry date</label>
                      <input placeholder="Month" style="width: 48%; float: left;" v-model="month" class="input--text" type="text">
                      <input placeholder="Year" style="width: 48%; float: right;" v-model="year" class="input--text" type="text">
                  </div>
                  <div class="input__container length" style="padding-top: 25px; font-size: 0.9em;">
                      <label style="width: 53%; float: left;" class="input__label" for="patient_name">security code</label>
                      <label style="width: 47%; float: left;" class="input__label" for="patient_name">zip code</label>
                      <input placeholder="CVV" style="width: 48%; float: left;" v-model="cardCvc" class="input--text" type="text">
                      <input placeholder="Enter zip" style="width: 48%; float: right;" v-model="postalCode" class="input--text" type="text">
                  </div>
                </div>
              </div>
          </div>
        </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px; padding-top: 35px;">
          <div class="input__container">
              <label class="input__label" for="patient_name">order status</label>
              <label class="input__label" style="color: #737373;">{{ status }}</label>
          </div>
          <div v-if="status === 'Recommended' && $root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'patient'" class="inline-centered">
            <button :disabled="!hasCard && (!cardCvc || !cardNumber || !month || !year || !postalCode || !firstName || !lastName)" @click="updateLabOrder" class="button" style="margin-top: 35px;">Complete Shipment</button>
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
            <div v-for="val in samples" class="input__container">
                <label class="input__label" for="patient_name">{{ capitalize(val) }}</label>
                <label class="input__label" style="color: #737373;">Required</label>
            </div>
        </div>
        <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
            <div class="input__container">
                <label class="input__label" for="patient_name">shipping address</label>
                <label class="input__label" style="color: #737373;">{{ addressOne }}</label>
                <label class="input__label" style="color: #737373;">{{ addressTwo }}</label>
                <label class="input__label" style="color: #737373;">{{ zip && city && state ? `${city}, ${state} ${zip}` : `` }}</label>
                <label class="input__label" style="color: #737373;">{{ zip && city && state && addressOne ? '' : 'No Shipping Address' }}</label>
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
              <label class="input__label" for="patient_name">billing info</label>
              <div v-if="$root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type !== 'patient' && status !== 'Recommended'">
                <label class="input__label" style="color: #737373;">{{`Billed to: ${card.brand} ****${card.last4}`}}</label>
                <label class="input__label" style="color: #737373;">{{`Charged: $${price}`}}</label>
              </div>
              <label v-if="$root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type !== 'patient' && status === 'Recommended'" class="input__label" style="color: #737373;">Not Paid Yet</label>
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
import _ from 'lodash'
export default {
  name: 'DetailLabOrders',
  props: ['row-data', 'reset'],
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
      selectedAddressTwo: null,
      firstName: '',
      lastName: '',
      month: '',
      year: '',
      cardNumber: '',
      cardExpiry: '',
      cardCvc: '',
      postalCode: '',
      hasCard: false,
      capitalize: _.capitalize
    }
  },
  methods: {
    handleFlyoutClose() {
      this.$parent.selectedRowData = null;
      this.$parent.detailFlyoutActive = !this.$parent.detailFlyoutActive
    },
    updateStatus(e) {
        this.selectedStatus = e.target.value;
    },
    updateTest(e, object) {
      this.selectedShipment[object.test_id] = e.target.value;
    },
    updateLabOrder() {
      if (!this.hasCard) {
        Stripe(window.Laravel.services.stripe.key)
        let card = Stripe.card.createToken({
            number: this.cardNumber,
            exp_month: this.month,
            exp_year: this.year,
            cvc: this.cardCvc,
            address_zip: this.postalCode,
            name: `${this.firstName} ${this.lastName}`
        }, (status, response) => {
            axios.post(`${this.$root.$data.apiUrl}/users/${this.$root.$data.global.user.id}/cards`, {id: response.id})
            .then(resp => {
              axios.patch(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, {
                shipment_code: this.$props.rowData.shipment_code,
                address_1: this.$props.rowData.address_1,
                address_2: this.$props.rowData.address_2,
                city: this.$props.rowData.city,
                state: this.$props.rowData.state,
                zip: this.$props.rowData.zip
              })
              .then(respond => {
                this.$parent.notificationMessage = "Successfully updated!";
                this.$parent.notificationActive = true;
                this.$parent.selectedRowData = null;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
                this.handleFlyoutClose()
              })
            })
        })
      } else {
        axios.patch(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, {
          shipment_code: this.$props.rowData.shipment_code,
          address_1: this.$props.rowData.address_1,
          address_2: this.$props.rowData.address_2,
          city: this.$props.rowData.city,
          state: this.$props.rowData.state,
          zip: this.$props.rowData.zip
        })
        .then(respond => {
          this.$parent.notificationMessage = "Successfully updated!";
          this.$parent.notificationActive = true;
          this.$parent.selectedRowData = null;
          setTimeout(() => this.$parent.notificationActive = false, 3000);
          this.handleFlyoutClose()
        })
      }
    },
    updateOrder() {
      this.$props.rowData.test_list.forEach(e => {
        if (this.selectedShipment[Number(e.test_id)] != undefined) {
          axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
            status: this.selectedShipment[Number(e.test_id)].toLowerCase()
          })
        }
      })
      axios.get(`${this.$root.$data.apiUrl}/lab/orders?include=patient,user`)
        .then(response => {
            this.$root.$data.global.labOrders = response.data.data.map((e, i) => {
                e['included'] = response.data.included[i]
                return e;
            })
            this.$root.$data.global.loadingLabOrders = false
            axios.get(`${this.$root.$data.apiUrl}/lab/tests?include=sku`)
            .then(response => {
                this.$root.$data.global.labTests = response.data.data.map((e, i) => {
                    e['included'] = response.data.included[i]
                    return e;
                })
                this.$root.$data.global.loadingLabTests = false
                this.$props.reset();
            })
        })
      this.$parent.notificationMessage = "Successfully updated!";
      this.$parent.notificationActive = true;
      this.$parent.selectedRowData = null;
      setTimeout(() => this.$parent.notificationActive = false, 3000);
      this.handleFlyoutClose();
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
    card() {
      if (this.$props.rowData && this.$props.rowData.card && this.$props.rowData.card.last4 && this.$props.rowData.card.brand) {
        this.hasCard = true
      }
      return this.$props.rowData ? this.$props.rowData.card : ''
    },
    price() {
      return this.$props.rowData ? this.$props.rowData.total_price : ''
    },
    samples() {
      return this.$props.rowData ? Object.keys(this.$props.rowData.samples) : []
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
