<template>
  <Flyout 
    :active="$parent.detailFlyoutActive" 
    :heading="flyoutHeading" 
    :on-close="handleFlyoutClose"
    :back="step == 2 ? prevStep : step == 3 ? prevStep : null"
  >

    <!-- PATIENTS ONLY -->

    <div v-if="$root.$data.permissions === 'patient'">

      <!-- RECOMMENDED -->

      <div v-if="step == 1">

        <!-- Doctor -->

        <div class="input__container">
          <label class="input__label">Doctor</label>
          <label class="input__item">{{ doctorName }}</label>
        </div>

        <!-- Lab Tests -->

        <div class="input__container">
          <label class="input__label first">Lab Tests</label>
          <a v-if="status !== 'Recommended' && status !== 'Confirmed'" v-for="test in testList" :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${test.shipment_code}&cntry_code=us`" class="input__label link-color"><i class="fa fa-medkit" aria-hidden="true"></i> {{ test.name }}</a>
          <a v-if="status === 'Confirmed'" v-for="test in testList" href="#" class="input__label link-color"><i class="fa fa-flask" aria-hidden="true"></i> {{ test.name }}</a>
          <div v-if="status === 'Recommended'">
            <div v-for="test in Object.values(patientTestList)" :class="{highlightCheckbox: test.checked}" class="inventory-left">
              <label :class="{'link-color': test.patient, highlightText: test.checked}" class="radio--text">
                <input :checked="test.checked" @click="updatePatientTests($event, test)" class="form-radio" type="checkbox">
                {{ test.attributes.name }} <i v-if="test.patient" class="fa fa-star" aria-hidden="true"></i>
              </label>
            </div>
          </div>
        </div>

        <!-- Tracking -->

        <div v-if="status !== 'Recommended' && status !== 'Confirmed'" class="input__container">
          <label class="input__label">Master Tracking</label>
          <label class="input__item">{{ shipmentCode }}</label>
        </div>

        <!-- Address -->

        <div v-if="status !== 'Recommended'" class="input__container">
          <label class="input__label">Address</label>
          <label class="input__item">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</label>
          <label class="input__item">{{ city }}, {{ state }} {{ zip }}</label>
        </div>

        <!-- Status -->

        <div class="input__container">
          <label class="input__label">Status</label>
          <label class="input__item">{{ status }}</label>
        </div>


        <!-- Call to Action -->

        <div v-if="status === 'Recommended' && $root.$data.permissions === 'patient'" class="button-wrapper">
          <button @click="stepThree" :disabled="disabled" class="button">Continue <i class="fa fa-long-arrow-right"></i></button>
        </div>

      </div>

      <!-- RECOMMENDED / PAYMENT -->

      <div v-if="step == 3">

        <!-- Product List -->

        <div class="input__container checkout-container">
          <div class="left-column">
            <label class="input__label" for="products">Products</label>
            <span class="sub-items" v-for="test in Object.values(labPatients)" disabled>{{ test.attributes.name }}</span>
          </div>
          <div class="right-column">
            <label class="input__label" for="total">Total</label>
            <span class="sub-items" v-for="test in Object.values(labPatients)">${{ test.attributes.price }}.00</span>
          </div>
          <div class="left-column">
            <label class="input__label discount" for="totals">Discount (20%)</label>
            <label class="input__label total" for="totals">Total</label>
          </div>
          <div class="right-column">
            <label class="input__label discount" for="price">$20.00</label>
            <label class="input__label total" for="price">${{ patientPrice }}</label>
          </div>
        </div>

        <!-- Address -->

        <div class="input__container">
          <label class="input__label">Address</label>
            <input placeholder="Address 1" v-model="address1" class="input--text address" type="text">
            <input placeholder="Address 2" v-model="address2" class="input--text address" type="text">
            <input placeholder="City" v-model="newCity" class="input--text city" type="text">
            <span class="custom-select state">
              <select @change="updateState($event)">
                  <option v-for="state in stateList" :data-id="state">{{ state }}</option>
              </select>
            </span>
            <input placeholder="Zip Code" v-model="newZip" class="input--text zip" type="text">

            <label v-if="!validZip" class="input__label">Please enter a valid zip code.</label>
        </div>

        <!-- Payment -->

        <div class="input__container">
          <label class="input__label" for="billing">Payment</label>
            <label class="input__item left-column">{{`${latestCard.brand} ****${latestCard.last4}`}}</label>
            <router-link class="right-column link-color" to="/settings">Edit Card</router-link>
        </div>

        <!-- Call to Action -->

        <div class="button-wrapper">
          <button class="button" :disabled="!address1 || !newCity || !newState || !newZip || !hasCard || !latestCard" @click="patientLabUpdate()">Confirm Payment</button>
        </div>
      </div>

    </div> <!-- END // PATIENT ONLY -->

    <!-- ADMINS/PRACTITIONERS ONLY -->

    <div v-if="$root.$data.permissions !== 'patient'">

      <!-- SHIPPED & BEYOND -->

      <div v-if="step === 1">

        <!-- Client -->

        <div class="input__container">
          <label class="input__label">Client</label>
          <span class="input__item">{{ patientName }}</span>
        </div>

        <!-- Doctor -->

        <div class="input__container">
          <label class="input__label">Doctor</label>
          <span class="input__item">{{ doctorName }}</span>
        </div>

        <!-- Lab Tests -->

        <div class="input__container">
          <label class="input__label">Lab Tests</label>
          <div v-for="test in testList">
            <a v-if="status === 'Recommended' || status === 'Confirmed'" href="#" class="input__label lab-test link-color"><i class="fa fa-flask" aria-hidden="true"></i> {{ test.name }}</a>
            <a v-if="status !== 'Recommended' && status !== 'Confirmed'" :href="`http://printtracking.fedex.com/trackOrder.do?gtns=${test.shipment_code}`" class="input__label link-color"><i class="fa fa-medkit" aria-hidden="true"></i> {{ test.name }}</a>
            <span class="custom-select">
                <select @change="updateTest($event, test)" class="disabled" disabled>
                    <option v-for="current in test.status">{{ current }}</option>
                </select>
            </span>
          </div>
        </div>

        <!-- Master Tracking -->

        <div v-if="status !== 'Recommended' && status !== 'Confirmed'" class="input__container">
          <label class="input__label">Master Tracking</label>
          <a :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${shipmentCode}&cntry_code=us`" class="input__item link-color"><i class="fa fa-truck" aria-hidden="true"></i> {{ shipmentCode }}</a>
        </div>

        <!-- Address -->

        <div class="input__container">
          <span class="input__label">Address</span>
          <div class="left-column">
            <span class="input__item">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</span>
            <span class="input__item">{{ zip && city && state ? `${city}, ${state} ${zip}` : `` }}</span>
            <span class="input__item left-column error-text">{{ zip && city && state && addressOne ? '' : 'No address on file.' }}</span>
          </div>
          <router-link class="input__item right-column link-color" :to="'/profile/' + patientUser">Edit Address</router-link>
        </div>

        <!-- Payment -->

        <div v-if="status !== 'Recommended'" class="input__container">
          <label class="input__label">Payment</label>
          <div v-if="$root.$data.permissions !== 'patient' && status !== 'Recommended' && oldCard !== null && oldCard.brand != undefined && oldCard.last4 != undefined" class="left-column">
            <span class="input__item">{{`${oldCard.brand} ****${oldCard.last4}`}}</span>
            <span class="input__item">{{`Charged: $${price}.00`}}</span>
          </div>
          <div class="left-column" v-if="$root.$data.permissions !== 'patient' && status !== 'Recommended' && oldCard !== null && oldCard.brand == undefined && oldCard.last4 == undefined">
            <span class="input__item error-text">{{`No card on file.`}}</span>
          </div>
          <span v-if="$root.$data.permissions !== 'patient' && status === 'Recommended'" class="input__item left-column error-text">Invoice not paid.</span>
          <router-link class="input__item right-column link-color" :to="'/settings/' + patientUser">{{`Edit Card`}}</router-link>

        </div>

        <!-- Status -->

        <div class="input__container">
          <label class="input__label">Status</label>
          <span class="input__item">{{ status }}</span>
        </div>

        <!-- Call to Action -->

        <div class="button-wrapper">
          <button v-if="status !== 'Confirmed'" class="button" @click="updateTests()">Update Order</button>
          <button v-if="status === 'Confirmed'" class="button" @click="nextStep()">Enter Tracking <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>

      <!-- FLYER STEP #2 -->

      <div v-if="step == 2">
        <div v-for="test in testList">
          <div class="input__container">
            <label class="input__label">{{ test.name }}</label>
            <input v-model="shippingCodes[test.test_id]" class="input--text" type="text">
          </div>
        </div>

        <!-- Master Tracking -->

        <div class="input__container">
          <label class="input__label">Master Tracking</label>
          <input v-model="masterTracking" class="input--text" type="text">
        </div>

        <!-- Address -->

        <div class="input__container">
          <span class="input__label">Address</span>
          <div class="left-column">          
            <span class="input__item">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</span>
            <span class="input__item">{{ zip && city && state ? `${city}, ${state} ${zip}` : `` }}</span>
            <span class="input__item left-column error-text">{{ zip && city && state && addressOne ? '' : 'No address on file.' }}</span>
          </div>
          <router-link class="input__item right-column link-color" :to="'/profile/' + patientUser">Edit Address</router-link>
        </div>

        <!-- Mark as Shipped -->

        <div class="button-wrapper">
          <button class="button" @click="markedShipped()" :disabled="masterTracking.length == 0">Mark as Shipped</button>
        </div>
      </div>

    </div>

    <!-- MODAL -->

    <Modal :active="invalidModalActive" :onClose="closeInvalidCC">
      <div class="inline-centered">
        <h1>Invalid Credit Card</h1>
        <p>The credit card you entered is invalid.</p>
        <div class="button-wrapper">
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
        shippingCodes: {},
        selectedAddressOne: null,
        selectedAddressTwo: null,
        firstName: '',
        lastName: '',
        month: '',
        year: '',
        disabled: true,
        masterTracking: '',
        step: 1,
        address1: '',
        address2: '',
        newCity: '',
        newZip: '',
        newState: '',
        cardNumber: '',
        cardExpiry: '',
        cardCvc: '',
        patientPrice: 0,
        paid: {},
        patientLabTests: {},
        labPatients: {},
        postalCode: '',
        invalidCC: false,
        invalidModalActive: false,
        hasCard: this.$root.$data.global.creditCards.length,
        capitalize: _.capitalize,
        monthList: ['', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
      }
    },
    methods: {
      updatePatientTests(e, test) {
        this.patientTestList[test.attributes.name].checked = !test.checked;
        if (this.patientTestList[test.attributes.name].checked) {
          this.labPatients[test.attributes.name] = test;
        } else {
          delete this.labPatients[test.attributes.name];
        }
        let price = 0;
        Object.values(this.labPatients).forEach(e => {
          price += eval(e.attributes.price);
        })
        this.patientPrice = `${price}.00`;
        this.disabled = _.isEmpty(this.labPatients) ? true : false
      },
      handleFlyoutClose() {
        this.step = 1;
        this.$parent.selectedRowData = null;
        this.$parent.detailFlyoutActive = !this.$parent.detailFlyoutActive
      },
      updateStatus(e) {
        this.selectedStatus = e.target.value;
      },
      updateTest(e, object) {
        this.selectedShipment[object.test_id] = e.target.value;
      },
      isEmpty(obj) {
        return _.isEmpty(obj);
      },
      updateState(e) {
        this.newState = e.target.value
      },
      stepThree() {
        this.step = 3;
        this.flyoutHeading = 'Confirm Payment';
      },
      nextStep() {
        this.step++;
      },
      prevStep() {
        this.step = 1;
      },
      closeInvalidCC() {
        this.invalidCC = false;
        this.invalidModalActive = false;
      },
      updateMonth(e) {
        this.month = e.target.value
      },
      patientLabUpdate() {
        axios.patch(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, {
            address_1: this.address1,
            address_2: this.address2,
            city: this.newCity,
            state: this.newState,
            zip: this.newZip
          })
          .then(respond => {
              _.each(this.patientTestList, (e) => {
                if (e.patient && !e.checked) {
                  let id = null;
                  this.$props.rowData.test_list.forEach(ele => {
                    if (e.attributes.name === ele.name) {
                      id = ele.test_id;
                    }
                  })
                  axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${id}`, {
                    status: 'canceled'
                  })
                } else if (e.patient && e.checked) {
                  let id = null;
                  this.$props.rowData.test_list.forEach(ele => {
                    if (e.attributes.name === ele.name) {
                      id = ele.test_id;
                    }
                  })
                  axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${id}`, {
                    status: 'confirmed'
                  })
                } else if (!e.patient && e.checked) {
                  axios.post(`${this.$root.$data.apiUrl}/lab/tests`, {
                    lab_order_id: Number(this.$props.rowData.id),
                    sku_id: Number(e.id),
                    status: 'confirmed'
                  })
                }
            })
            this.$parent.notificationMessage = "Successfully updated!";
            this.$parent.notificationActive = true;
            this.$parent.selectedRowData = null;
            setTimeout(() => this.$parent.notificationActive = false, 3000);
            this.handleFlyoutClose()
          })
      },
      markedShipped() {
        axios.patch(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, {
            shipment_code: this.masterTracking,
            address_1: this.$props.rowData.address_1,
            address_2: this.$props.rowData.address_2,
            city: this.$props.rowData.city,
            state: this.$props.rowData.state,
            zip: this.$props.rowData.zip
          })
          .then(respond => {
              this.$props.rowData.test_list.forEach((e) => {
              if (this.selectedShipment[Number(e.test_id)] != undefined) {
                axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
                  status: this.selectedShipment[Number(e.test_id)].toLowerCase()
                })
              }
            })
            this.$parent.notificationMessage = "Successfully updated!";
            this.$parent.notificationActive = true;
            this.$parent.selectedRowData = null;
            setTimeout(() => this.$parent.notificationActive = false, 3000);
            this.handleFlyoutClose()
          })
      },
      updateLabOrder() {
        axios.patch(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, {
            shipment_code: this.$props.rowData.shipment_code,
            address_1: this.$props.rowData.address_1 ? this.$props.rowData.address_1 : this.address1,
            address_2: this.$props.rowData.address_2 ? this.$props.rowData.address_2 : this.address2,
            city: this.$props.rowData.city ? this.$props.rowData.city : this.newCity,
            state: this.$props.rowData.state ? this.$props.rowData.state : this.newState,
            zip: this.$props.rowData.zip ? this.$props.rowData.zip : this.newZip
          })
          .then(respond => {
              this.$props.rowData.test_list.forEach((e) => {
              if (this.selectedShipment[Number(e.test_id)] != undefined) {
                axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
                  status: this.selectedShipment[Number(e.test_id)].toLowerCase()
                })
              } else if (this.$props.rowData.completed_at === 'Confirmed') {
                axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
                  status: 'shipped',
                  shipment_code: this.shippingCodes[e.test_id],
                })
              } else if (this.$props.rowData.completed_at === 'Recommended') {
                axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
                  status: 'confirmed'
                })
              }
            })
            this.$parent.notificationMessage = "Successfully updated!";
            this.$parent.notificationActive = true;
            this.$parent.selectedRowData = null;
            setTimeout(() => this.$parent.notificationActive = false, 3000);
            this.handleFlyoutClose()
          })
      },
      updateTests() {
        this.$props.rowData.test_list.forEach((e) => {
          if (this.selectedShipment[Number(e.test_id)] != undefined) {
              axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
                status: this.selectedShipment[Number(e.test_id)].toLowerCase()
              })
            } else if (this.$props.rowData.completed_at === 'Confirmed') {
              axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
                status: 'shipped',
                shipment_code: this.shippingCodes[e.test_id],
              })
            } else if (this.$props.rowData.completed_at === 'Recommended') {
              axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
                status: 'confirmed'
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
          `Dr. ${this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.name}, ND` :
          ''
      },
      patientName() {
        return this.$props.rowData ?
          `${this.$root.$data.global.patientLookUp[Number(this.$props.rowData.patient_id)].attributes.name}` :
          ''
      },
      validZip() {
        if (this.zip != '') {
          return this.zip.split('').filter(e => Number(e) == e).length > 0 && this.zip.length == 5
        } else {
          return true
        }
      },
      id() {
        return this.$props.id ? this.$props.rowData.id : ''
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
      patientUser() {
        return this.$props.rowData ? this.$props.rowData.patient_user_id : null;
      },
      stateList() {
        return ["State", "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"]
      },
      oldCard() {
        if (this.$props.rowData && this.$props.rowData.card && this.$props.rowData.card.last4 && this.$props.rowData.card
          .brand) {
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
      patientTestList() {
        if (!this.$props.rowData) return {}
        let obj = {};
        this.$props.rowData.test_list.forEach(e => {
          obj[e.name] = e.test_id;
        })
        let objs = _.map(this.$root.$data.labTests, e => {
          e.patient = obj[e.attributes.name] ? true : false;
          e.checked = false;
          e.test_id = obj[e.attributes.name];
          return e;
        })
        let returns = {}
        objs.forEach(e => {
          returns[e.attributes.name] = e;
        })
        return returns;
      },
      latestCard() {
        return this.$root.$data.global.creditCards.slice(-1).pop();
      }
    }
  }

</script>
