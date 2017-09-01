<template>
  <Flyout :active="$parent.detailFlyoutActive" :heading="flyoutHeading" :on-close="handleFlyoutClose">
    <div v-if="$root.$data.permissions !== 'admin'">
      <div class="input__container">
        <label class="input__label first" for="patient_name">lab tests</label>
        <label v-for="test in testList" class="input__item">{{ test.name }} <a v-if="!test.cancel" style="color: #B4E7A0;">(Track Cli)</a></label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">doctor</label>
        <label class="input__item">{{ doctorName }}</label>
      </div>
      <div v-for="val in samples" class="input__container">
        <label class="input__label" for="patient_name">{{ val }}</label>
        <label class="input__item">Required</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">shipping address</label>
        <label class="input__item">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</label>
        <label class="input__item">{{ city }}, {{ state }} {{ zip }}</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">order tracking</label>
        <label class="input__item">{{ shipmentCode }}</label>
      </div>
      <div class="input__container" style="height: 475px;">
        <label class="input__label" for="patient_name">billing info</label>
        <div v-if="status !== 'Recommended'">
          <label class="input__item">{{`Billed to: ${oldCard.brand} ****${oldCard.last4}`}}</label>
          <label class="input__item">{{`Charged: $${price}`}}</label>
        </div>
        <div v-if="status === 'Recommended' && $root.$data.permissions === 'practitioner'">
          <label class="input__item">Not paid yet</label>
        </div>
        <div v-if="status === 'Recommended' && $root.$data.permissions === 'patient'">
          <div v-if="latestCard">
            <label class="input__item">{{`Billed to: ${latestCard.brand} ****${latestCard.last4}`}}</label>
            <label class="input__item">{{`Charged: $${price}`}}</label>
          </div>
          <div v-if="!latestCard" style="padding-top: 5px;">
            <div class="input__container length" style="margin-bottom: 1.5em; font-size: 0.9em;">
              <label class="input__label" for="patient_name">card number</label>
              <input placeholder="Enter card number" v-model="cardNumber" class="input--text" type="text">
            </div>
            <div class="input__container length" style="font-size: 0.9em;">
              <label class="input__label" for="patient_name">name on card</label>
              <input placeholder="First name" style="width: 48%; float: left;" v-model="firstName" class="input--text" type="text">
              <input placeholder="Last name" style="width: 48%; float: right;" v-model="lastName" class="input--text" type="text">
            </div>
            <div class="input__container length" style="padding-top: 25px;">
              <label class="input__label" for="patient_name">expiry date</label>
              <span class="custom-select" style="float: left; width: 48%;">
                  <select @change="updateMonth($event)">
                      <option v-for="month in monthList">{{ month }}</option>
                  </select>
              </span>
              <input placeholder="Year" style="width: 48%; float: right;" v-model="year" class="input--text" type="text">
            </div>
            <div class="input__container length" style="padding-top: 25px;">
              <label style="width: 53%; float: left;" class="input__label" for="patient_name">security code</label>
              <label style="width: 47%; float: left;" class="input__label" for="patient_name">zip code</label>
              <input placeholder="CVV" style="width: 48%; float: left;" v-model="cardCvc" class="input--text" type="text">
              <input placeholder="Enter zip" style="width: 48%; float: right;" v-model="postalCode" class="input--text" type="text">
            </div>
          </div>
        </div>
      </div>
      <div style=" padding-top: 35px;">
        <div class="input__container">
          <label class="input__label" for="patient_name">order status</label>
          <label class="input__item">{{ status }}</label>
        </div>
        <div v-if="status === 'Recommended' && $root.$data.permissions === 'patient'" class="inline-centered">
          <button :disabled="!hasCard && (!cardCvc || !cardNumber || !month || !year || !postalCode || !firstName || !lastName)" @click="updateLabOrder"
            class="button" style="margin-top: 35px;">Complete Shipment</button>
        </div>
      </div>
    </div>
    <div v-if="$root.$data.permissions === 'admin'">
      <div class="input__container">
        <label class="input__label" for="patient_name">lab tests</label>
        <div v-for="test in testList">
          <label class="input__label" style="border: none; padding-top: 7.5px;">{{ test.name }}</label>
          <span class="custom-select">
                <select @change="updateTest($event, test)">
                    <option v-for="current in test.status">{{ current }}</option>
                </select>
            </span>
        </div>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">doctor</label>
        <span class="input--text">{{ doctorName }}</span>
      </div>
      <div v-for="val in samples" class="input__container">
        <label class="input__label" for="patient_name">{{ capitalize(val) }}</label>
        <label class="input__item">Required</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">shipping address</label>
        <label class="input__item">{{ addressOne }}</label>
        <label class="input__item">{{ addressTwo }}</label>
        <label class="input__item">{{ zip && city && state ? `${city}, ${state} ${zip}` : `` }}</label>
        <label class="input__item">{{ zip && city && state && addressOne ? '' : 'No Shipping Address' }}</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">order tracking</label>
        <a :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${shipmentCode}&cntry_code=us`" class="input__item" style="color: #82BEF2;">{{ shipmentCode }}</a>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">billing info</label>
        <div v-if="$root.$data.permissions !== 'patient' && status !== 'Recommended'">
          <label class="input__item">{{`Billed to: ${oldCard.brand} ****${oldCard.last4}`}}</label>
          <label class="input__item">{{`Charged: $${price}`}}</label>
        </div>
        <label v-if="$root.$data.permissions !== 'patient' && status === 'Recommended'" class="input__item">Not Paid Yet</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">order status</label>
        <span class="input--text">{{ status }}</span>
      </div>
      <div class="inline-centered">
        <button class="button" @click="updateOrder()">Update Shipment</button>
      </div>
    </div>
    <Modal :active="invalidModalActive" :onClose="closeInvalidCC">
        <div class="inline-centered">
            <h1>Invalid Credit Card</h1>
            <p>The credit card you entered is invalid.</p>
            <div class="inline-centered">
                <button @click="closeInvalidCC" class="button">Try again</button>
            </div>
        </div>
    </Modal>
  </Flyout>
</template>

<script>
  import Flyout from '../../../commons/Flyout.vue'
  import Modal from '../../../commons/Modal.vue'
  import SelectOptions from '../../../commons/SelectOptions.vue'
  import {
    capitalize
  } from '../../../utils/filters/textformat'
  import axios from 'axios'
  import _ from 'lodash'
  export default {
    name: 'DetailLabOrders',
    props: ['row-data', 'reset'],
    components: {
      Flyout,
      SelectOptions,
      Modal
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
        invalidCC: false,
        invalidModalActive: false,
        hasCard: this.$root.$data.global.creditCards.length,
        capitalize: _.capitalize,
        latestCard: this.$root.$data.global.creditCards.slice(-1).pop(),
        monthList: ['','1','2','3','4','5','6','7','8','9','10','11','12']
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
      closeInvalidCC() {
        this.invalidCC = false;
        this.invalidModalActive = false;
      },
      updateMonth(e) {
          this.month = e.target.value
      },
      updateLabOrder() {
        if (!this.hasCard) {
          let card = Stripe.card.createToken({
            number: this.cardNumber,
            exp_month: this.month,
            exp_year: this.year,
            cvc: this.cardCvc,
            address_zip: this.postalCode,
            name: `${this.firstName} ${this.lastName}`
          }, (status, response) => {
            if (response.error) {
              this.invalidCC = true;
              this.invalidModalActive = true;
              this.handleFlyoutClose();
              return;
            }
            axios.post(`${this.$root.$data.apiUrl}/users/${this.$root.$data.global.user.id}/cards`, {
                id: response.id
              })
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
        return this.$props.rowData ?
          `Dr. ${this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.name}` :
          ''
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
      oldCard() {
        if (this.$props.rowData && this.$props.rowData.card && this.$props.rowData.card.last4 && this.$props.rowData.card
          .brand) {
          this.hasCard = true
        }
        return this.$props.rowData ? this.$props.rowData.card : {brand: null, last4: null}
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
        data.name =
          `Dr. ${this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.name}`
        data.id = this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].id
        data.user_id = this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes
          .user_id
        let arr = _.pull(this.$root.$data.global.practitioners, data)
        return [data].concat(arr)
      },
      statusList() {
        if (!this.$props.rowData) return ""
        return this.$props.rowData.completed_at
      },
      testList() {
        if (!this.$props.rowData) return []
        this.$props.rowData.test_list = this.$props.rowData && this.$props.rowData.test_list.length == 0 ? [{
          name: "No Lab Orders",
          cancel: true,
          status: ['No Order']
        }] : this.$props.rowData.test_list
        return this.$props.rowData.test_list
      }
    }
  }

</script>
