<template>
  <Flyout :active="$parent.detailFlyoutActive" :heading="flyoutHeading" :on-close="handleFlyoutClose">
    <div v-if="$root.$data.permissions !== 'admin'">
      <div class="input__container">
        <label class="input__label first" for="patient_name">Lab Tests</label>
        <a v-for="test in testList" :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${test.shipment_code}&cntry_code=us`" class="input__item" style="color: #82BEF2; width: 100%; float: left;">{{ test.name }}</a>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Doctor</label>
        <label class="input__item">{{ doctorName }}</label>
      </div>
      <div v-for="val in samples" class="input__container">
        <label class="input__label" for="patient_name">{{ val }}</label>
        <label class="input__item">Required</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Mailing Address</label>
        <label class="input__item">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</label>
        <label class="input__item">{{ city }}, {{ state }} {{ zip }}</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Order Tracking</label>
        <label class="input__item">{{ shipmentCode }}</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Billing</label>
        <div v-if="status !== 'Recommended'">
          <label v-if="oldCard !== null && oldCard.brand !== null && oldCard.last4 !== null" class="input__item">{{`Billed to: ${oldCard.brand} ****${oldCard.last4}`}}</label>
          <label v-if="!oldCard || !oldCard.brand || !oldCard.last4" class="input__item">{{`No credit card on file`}}</label>
          <label class="input__item">{{`Charged: $${price}`}}</label>
        </div>
        <div v-if="status === 'Recommended' && $root.$data.permissions === 'practitioner'">
          <label class="input__item">Unpaid</label>
        </div>
        <div v-if="status === 'Recommended' && $root.$data.permissions === 'patient'">
          <div v-if="latestCard">
            <label class="input__item">{{`Billed to: ${latestCard.brand} ****${latestCard.last4}`}}</label>
            <label class="input__item">{{`Charged: $${price}`}}</label>
          </div>
          <div v-if="!latestCard" style="padding-top: 5px;">
            <router-link to="/settings">Add a credit card to complete shipment.</router-link>
          </div>
        </div>
      </div>
      <div style=" padding-top: 35px;">
        <div class="input__container">
          <label class="input__label" for="patient_name">Order Status</label>
          <label class="input__item">{{ status }}</label>
        </div>
        <div v-if="status === 'Recommended' && $root.$data.permissions === 'patient'" class="inline-centered">
          <button :disabled="!hasCard && !latestCard" @click="updateLabOrder"
            class="button" style="margin-top: 35px;">Complete Shipment</button>
        </div>
      </div>
    </div>
    <div v-if="$root.$data.permissions === 'admin'">
      <div class="input__container">
        <label class="input__label" for="patient_name">Lab Tests</label>
        <div v-for="test in testList">
          <a :href="`http://printtracking.fedex.com/trackOrder.do?gtns=${test.shipment_code}`" class="input__label" style="border: none; padding-top: 7.5px; color: rgb(130, 190, 242);">{{ test.name }}</a>
          <span class="custom-select">
                <select @change="updateTest($event, test)">
                    <option v-for="current in test.status">{{ current }}</option>
                </select>
            </span>
        </div>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Doctor</label>
        <span class="input--text">{{ doctorName }}</span>
      </div>
      <div v-for="val in samples" class="input__container">
        <label class="input__label" for="patient_name">{{ capitalize(val) }}</label>
        <label class="input__item">Required</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Mailing Address</label>
        <label class="input__item">{{ addressOne }}</label>
        <label class="input__item">{{ addressTwo }}</label>
        <label class="input__item">{{ zip && city && state ? `${city}, ${state} ${zip}` : `` }}</label>
        <label class="input__item">{{ zip && city && state && addressOne ? '' : 'No Shipping Address' }}</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Order Tracking</label>
        <a :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${shipmentCode}&cntry_code=us`" class="input__item" style="color: #82BEF2;">{{ shipmentCode }}</a>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Billing</label>
        <div v-if="$root.$data.permissions !== 'patient' && status !== 'Recommended' && oldCard !== null && oldCard.brand != undefined && oldCard.last4 != undefined">
          <label class="input__item">{{`Billed to: ${oldCard.brand} ****${oldCard.last4}`}}</label>
          <label class="input__item">{{`Charged: $${price}`}}</label>
        </div>
        <label v-if="$root.$data.permissions !== 'patient' && status !== 'Recommended' && oldCard !== null && oldCard.brand == undefined && oldCard.last4 == undefined" class="input__item">{{`No credit card on file`}}</label>
        <label v-if="$root.$data.permissions !== 'patient' && status === 'Recommended'" class="input__item">Not Paid Yet</label>
      </div>
      <div class="input__container">
        <label class="input__label" for="patient_name">Order Status</label>
        <span class="input--text">{{ status }}</span>
      </div>
      <div class="inline-centered">
        <button class="button" @click="updateOrder()">Update Order</button>
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
      },
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
        if (this.$props.rowData && this.$props.rowData.card && this.$props.rowData.card.last4 && this.$props.rowData.card.brand) {
          this.hasCard = true
        }
        return this.$props.rowData ? this.$props.rowData.card : null
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
      },
      latestCard() {
        return this.$root.$data.global.creditCards.slice(-1).pop();
      }
    }
  }

</script>
