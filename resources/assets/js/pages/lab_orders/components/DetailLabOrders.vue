<template>
  <Flyout :active="$parent.detailFlyoutActive" :heading="$parent.step === 3 ? 'Confirm Payment' : flyoutHeading" :on-close="handleFlyoutClose" :back="$parent.step == 2 ? prevStep : $parent.step == 3 ? prevStep : null">

    <!-- PATIENTS -->

    <div v-if="$root.$data.permissions === 'patient'">

      <div v-if="$parent.step == 1">

        <!-- Doctor -->

        <div class="input__container">
          <label class="input__label">Doctor</label>
          <label class="input__item">{{ doctorName }}</label>
        </div>

        <!-- Lab Tests -->

        <div class="input__container">
          <label class="input__label first">Lab Tests</label>

          <!-- Recommended -->

          <div v-if="status === 'Recommended'">
            <div v-for="test in Object.values(patientTestList)" :class="{highlightCheckbox: test.checked}" class="inventory-left custom-padding">
              <label :class="{'link-color': test.patient, highlightText: test.checked}" class="radio--text">
                <input :checked="test.checked" @click="updatePatientTests($event, test)" class="form-radio" type="checkbox"> {{ test.attributes.name }} </input>
                <i v-if="test.patient" class="fa fa-star" aria-hidden="true"></i>
              </label>
            </div>
          </div>

          <!-- Confirmed -->

        <div v-if="status === 'Confirmed'">
          <div v-for="test in testList" class="sub-items">
            <i class="fa fa-flask" aria-hidden="true"></i> {{ test.name }}
          </div>
        </div>

          <!-- Shipped or greater -->
          <div v-if="status !== 'Recommended' && status !== 'Confirmed'">
            <a v-for="test in testList" :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${test.shipment_code}&cntry_code=us`" class="sub-items link-color" target="_blank">
              <i class="fa fa-truck" aria-hidden="true"></i> {{ test.name }}
            </a>
          </div>

        </div>

        <!-- Tracking -->

        <!-- <div v-if="status !== 'Recommended' && status !== 'Confirmed'" class="input__container">
          <label class="input__label">Master Tracking</label>
          <label class="input__item">{{ shipmentCode }}</label>
        </div> -->

        <!-- Address -->

        <div v-if="status !== 'Recommended'" class="input__container">
          <label class="input__label">Address</label>
          <label class="input__item">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</label>
          <label class="input__item">{{ city }}, {{ state }} {{ zip }}</label>
        </div>

        <!-- Card -->

        <!-- Show only if the invoice is unpaid... -->
        <div v-if="status !== 'Recommended'" class="input__container">
          <label class="input__label">Card</label>
          <div class="left-column">
            <label v-if="latestCard && latestCard.brand && latestCard.last4" class="input__item">{{`${latestCard.brand} ****${latestCard.last4}`}}</label>
            <span v-else class="input__item error-text">No card on file.</span>
          </div>
          <!-- This should always show, don't add conditional statements -->
          <router-link class="right-column link-color" to="/settings">Edit Card</router-link>
        </div>

        <!-- Invoice -->

        <div v-if="status !== 'Recommended'" class="input__container">
          <label class="input__label">Invoice</label>
          <div class="left-column">
            <label v-if="oldCard && oldCard.brand && oldCard.last4" class="input__item">{{`${oldCard.brand} ****${oldCard.last4}`}}</label>
            <span v-if="oldCard && oldCard.brand && oldCard.last4" class="input__item color-good">{{`Charged: $${invoicePrice || price}`}}</span>
            <span v-else class="input__item error-text">Invoice unpaid.</span>
          </div>
        </div>

        <!-- Status -->

        <div class="input__container">
          <label class="input__label">Status</label>
          <label class="input__item">{{ status }}</label>
        </div>

        <!-- Discount Code -->
        <div v-if="status === 'Recommended'">
          <label class="input__label">Discount Code</label>
          <input placeholder="Discount Code" v-model="discountCode" @keydown="keyDownDiscountCode" class="input--text" type="text">
          <span class="error-text" v-if="disabledDiscount">Code does not exist.</span>
        </div>

        <!-- Call to Action -->

        <div v-if="status === 'Recommended' && $root.$data.permissions === 'patient'" class="button-wrapper">
          <button @click="validDiscountCode" :disabled="disabled" class="button">Continue
            <i class="fa fa-long-arrow-right"></i>
          </button>
        </div>

      </div>

      <!-- RECOMMENDED / PAYMENT -->

      <div v-if="$parent.step == 3">

        <!-- Product List -->

        <div class="input__container checkout-container">

          <!-- Product Names -->
          <div class="left-column">
            <label class="input__label" for="products">Products</label>
            <span class="sub-items" v-for="test in Object.values(labPatients)">{{ test.attributes.name }}</span>
          </div>

          <!-- Product Prices -->
          <div class="right-column">
            <label class="input__label" for="total">Total</label>
            <span class="sub-items" v-for="test in Object.values(labPatients)">${{ test.attributes.price }}</span>
          </div>

          <!-- Summary Names -->
          <div class="left-column">
            <label class="sub-items processing">Processing</label>
            <label v-if="discountCode" class="sub-items summary subtotal">Subtotal</label>
            <label v-if="discountCode" class="sub-items discount">
              <em>Discount ({{ discountType === 'dollars' ? `$${discountAmount}` : discountType === 'percent' ? `${discountAmount}%` : '0%' }})</em>
            </label>
            <label class="sub-items summary total">Total</label>
          </div>

          <!-- Summary Amounts -->
          <div class="right-column">
            <label class="sub-items processing">${{ processingFee.toFixed(2)  }}</label>
            <label v-if="discountCode" class="sub-items summary subtotal">${{ subtotalAmount.toFixed(2)  }}</label>
            <label v-if="discountCode" class="sub-items discount">
              <em>- ${{ discountType === 'dollars' ? `${discountAmount.toFixed(2)}` : discountType === 'percent' ? `${percentAmount}` : '0.00' }}</em>
            </label>
            <label class="sub-items summary total">${{ discountType === '' ? subtotalAmount.toFixed(2) : patientPrice }}</label>
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

        <!-- Card -->

        <div class="input__container">
          <label class="input__label">Card</label>
          <div class="left-column">
            <label v-if="latestCard && latestCard.brand && latestCard.last4" class="input__item">{{`${latestCard.brand} ****${latestCard.last4}`}}</label>
            <span v-else class="input__item error-text">No card on file.</span>
          </div>
          <!-- This should always show, don't add conditional statements -->
          <router-link class="right-column link-color" :to="'/settings'">Edit Card</router-link>
        </div>

        <!-- Invoice -->

        <div v-if="status !== 'Recommended'" class="input__container">
          <label class="input__label">Invoice</label>
          <div class="left-column">
            <label v-if="oldCard && oldCard.brand && oldCard.last4" class="input__item">{{`${oldCard.brand} ****${oldCard.last4}`}}</label>
            <span v-if="oldCard && oldCard.brand && oldCard.last4" class="input__item color-good">{{`Charged: $${invoicePrice || price}`}}</span>
            <span v-else class="input__item error-text">Invoice unpaid.</span>
          </div>
        </div>

        <!-- Call to Action -->

        <div class="button-wrapper">
          <button class="button" :disabled="!address1 || !newCity || !newState || newZip.length !== 5|| !latestCard.last4 || !latestCard.brand" @click="patientLabUpdate">Confirm Payment</button>
        </div>

        <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>

      </div>

    </div>
    <!-- END // PATIENT ONLY -->

    <!-- ADMINS/PRACTITIONERS -->

    <div v-if="$root.$data.permissions !== 'patient'">

      <div v-if="$parent.step === 1">

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
          <div v-for="test in testList" class="is-padding-bottom">

            <!-- Recommended or Confirmed -->

            <div v-if="status === 'Recommended' || status === 'Confirmed'" class="sub-items">
              <i class="fa fa-flask" aria-hidden="true"></i> {{ test.name }}
            </div>

            <!-- Shipped or Greater -->

            <a v-if="status !== 'Recommended' && status !== 'Confirmed'" :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${test.shipment_code}&cntry_code=us`" class="sub-items link-color" target="_blank">
              <i class="fa fa-truck" aria-hidden="true"></i> {{ test.name }}
            </a>

            <span class="custom-select">
              <select @change="updateTest($event, test)" :class="{disabled: status === 'Recommended' || status === 'Confirmed'}" :disabled="status === 'Recommended' || status === 'Confirmed'">
                <option v-for="current in test.status">{{ current }}</option>
              </select>
            </span>
          </div>
        </div>

        <!-- Master Tracking -->

        <!-- <div v-if="status !== 'Recommended' && status !== 'Confirmed'" class="input__container">
          <label class="input__label">Master Tracking</label>
          <a :href="`https://www.fedex.com/apps/fedextrack/index.html?tracknumbers=${shipmentCode}&cntry_code=us`" class="input__item link-color" target="_blank">
            <i class="fa fa-truck" aria-hidden="true"></i> {{ shipmentCode }}
          </a>
        </div> -->

        <!-- Address -->

        <div v-if="status !== 'Recommended'" class="input__container">
          <span class="input__label">Address</span>
          <div class="left-column">
            <span class="input__item">{{ addressOne }} {{ addressTwo ? addressTwo : '' }}</span>
            <span class="input__item">{{ zip && city && state ? `${city}, ${state} ${zip}` : `` }}</span>
            <span class="input__item left-column error-text">{{ zip && city && state && addressOne ? '' : 'No address on file.' }}</span>
          </div>
          <router-link class="input__item right-column link-color" :to="'/profile/' + patientUser">Edit Address</router-link>
        </div>

        <!-- Card -->

        <div class="input__container">
          <label class="input__label">Card</label>
          <div class="left-column">
            <span v-if="$parent.loading">
              <em>Loading cards...</em>
            </span>
            <label v-if="!$parent.loading && $parent.patientCard && $parent.patientCard.brand && $parent.patientCard.last4" class="input__item">{{`${$parent.patientCard.brand} ****${$parent.patientCard.last4}`}}</label>
            <span v-if="!$parent.loading && (!$parent.patientCard || !$parent.patientCard.brand || !$parent.patientCard.last4)" class="input__item error-text">No card on file.</span>
          </div>
          <!-- This should always show, don't add conditional statements -->
          <router-link class="input__item right-column link-color" :to="'/settings/' + patientUser">Edit Card</router-link>
        </div>

        <!-- Invoice -->

        <div v-if="status !== 'Recommended'" class="input__container">
          <label class="input__label">Invoice</label>
          <div class="left-column">
            <label v-if="oldCard && oldCard.brand && oldCard.last4" class="input__item">{{`${oldCard.brand} ****${oldCard.last4}`}}</label>
            <span v-if="paid" class="input__item color-good">{{`Charged: $${invoicePrice}`}}</span>
            <span v-if="!paid" class="input__item error-text">Invoice unpaid.</span>
          </div>
        </div>

        <!-- Status -->

        <div class="input__container">
          <label class="input__label">Status</label>
          <span class="input__item">{{ status }}</span>
        </div>

        <!-- Call to Action -->

        <div class="button-wrapper">
          <button v-if="status !== 'Confirmed' && status !== 'Recommended'" class="button" @click="updateLabOrder">Update Order</button>
          <button v-if="status === 'Confirmed'" class="button" @click="nextStep">Enter Tracking
            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
          </button>
        </div>

        <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>

      </div>
    </div>

    <!-- FLYER STEP #2 -->

    <div v-if="$parent.step == 2">

      <div v-for="test in testList">
        <div class="input__container">
          <label class="input__label">{{ test.name }}</label>
          <input v-model="shippingCodes[test.test_id]" class="input--text" type="text">
        </div>
      </div>

      <!-- Master Tracking -->

      <!-- <div class="input__container">
        <label class="input__label">Master Tracking</label>
        <input v-model="masterTracking" class="input--text" type="text">
      </div> -->

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
<!--
      <div class="button-wrapper">
        <button class="button" @click="markedShipped" :disabled="masterTracking.length == 0">Mark as Shipped</button>
      </div> -->

      <div class="button-wrapper">
        <button class="button" @click="startShipment">Start Shipment</button>
      </div>

      <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>

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
import Q from 'q';
import Flyout from '../../../commons/Flyout.vue';
import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
import Modal from '../../../commons/Modal.vue';
import SelectOptions from '../../../commons/SelectOptions.vue';
import axios from 'axios';
import _ from 'lodash';
export default {
  name: 'DetailLabOrders',
  props: {
    'row-data': Object,
    reset: Function
  },
  components: {
    Flyout,
    SelectOptions,
    Modal,
    ClipLoader
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
      discountCode: '',
      disabled: true,
      masterTracking: '',
      address1: '',
      address2: '',
      newCity: '',
      newZip: '',
      percentAmount: '',
      disabledDiscount: false,
      newState: '',
      patchCode: '',
      cardNumber: '',
      cardExpiry: '',
      cardCvc: '',
      discountType: '',
      discountAmount: 0, // Placeholder
      processingFee: 20,
      subtotalAmount: 0,
      pricing: 0,
      loading: false,
      patientPrice: 0,
      patientLabTests: {},
      labPatients: {},
      postalCode: '',
      invalidCC: false,
      invalidModalActive: false,
      monthList: ['', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
    };
  },
  methods: {
    updatePatientTests(e, test) {
      this.patientTestList[test.attributes.name].checked = !test.checked;
      if (this.patientTestList[test.attributes.name].checked) {
        this.labPatients[test.attributes.name] = test;
      } else {
        delete this.labPatients[test.attributes.name];
      }

      let prices = 0;
      _.each(this.labPatients, e => {
        prices += Number(e.attributes.price);
      });

      let fee = this.processingFee;
      let subtotal = prices + fee;
      this.processingFee = fee;
      this.subtotalAmount = subtotal;

      this.disabled = _.isEmpty(this.labPatients) ? true : false;
    },
    keyDownDiscountCode(e) {
      if (e.target.value === '') {
        this.disabledDiscount = false;
      }
    },
    handleFlyoutClose() {
      this.$parent.step = 1;
      this.loading = false;
      this.$parent.selectedRowData = null;
      this.$parent.detailFlyoutActive = !this.$parent.detailFlyoutActive;
      this.address1 = '';
      this.address2 = '';
      this.newCity = '';
      this.newZip = '';
      this.subtotalAmount = 0;
      this.discountAmount = 0;
      this.discountType = '';
      this.percentAmount = 0;
      this.disabledDiscount = false;
      this.discountCode = '';
      this.pricing = 0;
      this.newState = '';
      this.labPatients = {};
      this.$parent.setupLabData();
      if (this.$root.$data.permissions !== 'patient') {
        let status = {
          0: "Recommended",
          1: "Confirmed",
          2: "Shipped",
          3: "Received",
          4: "Mailed",
          5: "Processing",
          6: "Complete"
        };
        this.$parent.handleFilter(status[this.$parent.activeFilter], this.$parent.activeFilter);
      }
    },
    updateStatus(e) {
      this.selectedStatus = e.target.value;
    },
    updateTest(e, object) {
      this.selectedShipment[object.test_id] = e.target.value;
    },
    validDiscountCode() {
      if (this.discountCode !== '') {
        axios.get(`${this.$root.$data.apiUrl}/discountcode?discount_code=${this.discountCode}&applies_to=lab-test`)
          .then(response => {
            if (response.data.data.attributes.valid) {
              this.disabledDiscount = false;
              this.discountType = response.data.data.attributes.discount_type;
              this.discountAmount = Number(response.data.data.attributes.amount);
              if (this.discountType === 'percent') {
                this.percentAmount = (this.subtotalAmount * (Number(this.discountAmount) * 0.01)).toFixed(2);
              }
              this.patientPrice = this.discountType === 'percent' ? `${(this.subtotalAmount * (100 - Number(this.discountAmount)) * 0.01).toFixed(2)}` :
                this.discountType === 'dollars' ? `${eval(this.subtotalAmount - this.discountAmount).toFixed(2)}` : `${this.patientPrice.toFixed(2)}`;
              this.patchCode = response.data.data.attributes.code;
              this.stepThree();
            } else {
              this.disabledDiscount = true;
            }
            this.stepThree();
          })
          .catch(() => {
            this.disabledDiscount = true;
          });
      } else {
        this.stepThree();
        this.disabledDiscount = false;
      }
    },
    isEmpty(obj) {
      return _.isEmpty(obj);
    },
    updateState(e) {
      this.newState = e.target.value;
    },
    stepThree() {
      this.$parent.step = 3;
    },
    nextStep() {
      this.$parent.step++;
    },
    prevStep() {
      this.$parent.step = 1;
      this.discountAmount = 0;
      this.discountType = '';
      this.percentAmount = 0;
      this.disabledDiscount = false;
      this.discountCode = '';
    },
    closeInvalidCC() {
      this.invalidCC = false;
      this.invalidModalActive = false;
    },
    updateMonth(e) {
      this.month = e.target.value;
    },
    patientLabUpdate() {
      this.loading = true;
      let promises = [];
      _.each(this.patientTestList, (e) => {
        if (e.patient && !e.checked) {
          let id = null;
          this.$props.rowData.test_list.forEach(ele => {
            if (e.attributes.name === ele.name) {
              id = ele.test_id;
            }
          });
          promises.push(axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${id}`, {
            status: 'canceled'
          }).then(resp => {
            this.$root.$data.global.labTests.forEach((ele, idx) => {
              if (Number(ele.id) === Number(e.test_id)) {
                this.$root.$data.global.labTests[idx].attributes = resp.data.data.attributes;
              }
            });
          }));
        } else if (e.patient && e.checked) {
          let id = null;
          this.$props.rowData.test_list.forEach(ele => {
            if (e.attributes.name === ele.name) {
              id = ele.test_id;
            }
          });
          promises.push(axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${id}`, {
            status: 'confirmed'
          })
            .then(resp => {
              this.$root.$data.global.labTests.forEach((ele, idx) => {
                if (Number(ele.id) === Number(e.test_id)) {
                  this.$root.$data.global.labTests[idx].attributes = resp.data.data.attributes;
                }
              });
            }));
        } else if (!e.patient && e.checked) {
          promises.push(axios.post(`${this.$root.$data.apiUrl}/lab/tests`, {
            lab_order_id: Number(this.$props.rowData.id),
            sku_id: Number(e.id),
            status: 'confirmed'
          })
            .then(resp => {
              this.$root.$data.global.labTests.push(resp.data.data);
            }));
        }
      });
      return Q.allSettled(promises).then(() => {
        let data = null;
        if (this.discountCode) {
          data = {
            address_1: this.address1,
            address_2: this.address2,
            city: this.newCity,
            state: this.newState,
            zip: this.newZip,
            discount_code: this.discountCode
          };
        } else {
          data = {
            address_1: this.address1,
            address_2: this.address2,
            city: this.newCity,
            state: this.newState,
            zip: this.newZip
          };
        }
        axios.patch(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, data)
          .then((respond) => {
            let status = _.capitalize(respond.data.data.attributes.status);
            let number = _.size(this.labPatients);
            this.$root.$data.global.labOrders.forEach((e, i) => {
              if (e.id === this.$props.rowData.id) {
                this.$root.$data.global.labOrders[i].attributes.status = status;
                this.$root.$data.global.labOrders[i].attributes.address_1 = this.address1;
                this.$root.$data.global.labOrders[i].attributes.address_2 = this.address2;
                this.$root.$data.global.labOrders[i].attributes.city = this.newCity;
                this.$root.$data.global.labOrders[i].attributes.state = this.newState;
                this.$root.$data.global.labOrders[i].attributes.zip = this.newZip;
              }
            });
            this.$parent.currentData.forEach((e, i) => {
              if (e.data.id === this.$props.rowData.id) {
                this.$parent.currentData[i].data.completed_at = status;
                this.$parent.currentData[i].data.address_1 = this.address1;
                this.$parent.currentData[i].data.address_2 = this.address2;
                this.$parent.currentData[i].data.city = this.newCity;
                this.$parent.currentData[i].data.state = this.newState;
                this.$parent.currentData[i].data.zip = this.newZip;
                this.$parent.currentData[i].data.number_of_tests = number;
                this.$parent.currentData[i].values[5] = status;
                this.$parent.currentData[i].values[4] = number;
              }
            });
            this.$parent.notificationMessage = "Successfully updated!";
            this.$parent.notificationActive = true;
            this.$parent.selectedRowData = null;
            setTimeout(() => this.$parent.notificationActive = false, 3000);
            this.handleFlyoutClose();
          });
      });
    },
    startShipment() {
        this.loading = true;
        let promises = [];
        console.log(this.$props.rowData.test_list);

        // mark each test as shipped

        // update local data without need to push master tracking code (that needs to be done on the BE)
    },
    markedShipped() {
      this.loading = true;
      let promises = [];
      this.$props.rowData.test_list.forEach((e) => {
        promises.push(axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
          status: 'shipped'
        })
          .then(resp => {
            this.$root.$data.global.labTests.forEach((ele, idx) => {
              if (Number(ele.id) === Number(e.test_id)) {
                this.$root.$data.global.labTests[idx].attributes = resp.data.data.attributes;
              }
            });
          }));
      });
      return Q.allSettled(promises).then(() => {
        axios.patch(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, {
          shipment_code: this.masterTracking
        })
          .then(respond => {
            let status = _.capitalize(respond.data.data.attributes.status);
            this.$root.$data.global.labOrders.forEach((e, i) => {
              if (e.id === this.$props.rowData.id) {
                this.$root.$data.global.labOrders[i].attributes.status = status;
                this.$root.$data.global.labOrders[i].attributes.shipment_code = respond.data.data.attributes.shipment_code;
              }
            });
            this.$parent.currentData.forEach((e, i) => {
              if (e.data.id === this.$props.rowData.id) {
                this.$parent.currentData[i].data.completed_at = status;
                this.$parent.currentData[i].values[5] = status;
                this.$parent.currentData[i].data.shipment_code = respond.data.data.attributes.shipment_code;
              }
            });
            this.$parent.notificationMessage = "Successfully updated!";
            this.$parent.notificationActive = true;
            this.$parent.selectedRowData = null;
            setTimeout(() => this.$parent.notificationActive = false, 3000);
            this.handleFlyoutClose();
          });
      });
    },
    updateLabOrder() {
      let promises = [];
      this.$props.rowData.test_list.forEach((e) => {
        if (this.selectedShipment[Number(e.test_id)] != undefined) {
          promises.push(axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
            status: this.selectedShipment[Number(e.test_id)].toLowerCase()
          }).then(resp => {
            this.$root.$data.global.labTests.forEach((ele, idx) => {
              if (Number(ele.id) === Number(e.test_id)) {
                this.$root.$data.global.labTests[idx].attributes = resp.data.data.attributes;
              }
            });
          }));
        } else if (this.$props.rowData.completed_at === 'Confirmed') {
          promises.push(axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
            status: 'shipped',
            shipment_code: this.shippingCodes[e.test_id]
          }).then(resp => {
            this.$root.$data.global.labTests.forEach((ele, idx) => {
              if (Number(ele.id) === Number(e.test_id)) {
                this.$root.$data.global.labTests[idx].attributes = resp.data.data.attributes;
              }
            });
          }));
        } else if (this.$props.rowData.completed_at === 'Recommended') {
          promises.push(axios.patch(`${this.$root.$data.apiUrl}/lab/tests/${Number(e.test_id)}`, {
            status: 'confirmed'
          }).then(resp => {
            this.$root.$data.global.labTests.forEach((ele, idx) => {
              if (Number(ele.id) === Number(e.test_id)) {
                this.$root.$data.global.labTests[idx].attributes = resp.data.data.attributes;
              }
            });
          }));
        }
      });
      return Q.allSettled(promises).then(() => {
        axios.get(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}?include=user,patient,invoice`)
          .then(respond => {
            let user = respond.data.included.filter(e => e.type === 'users');
            let patient = respond.data.included.filter(e => e.type === 'patients');
            let invoices = respond.data.included.filter(e => e.type === 'invoices');
            this.$root.$data.global.labOrders.forEach((e, i) => {
              if (e.id === this.$props.rowData.id) {
                this.$root.$data.global.labOrders[i] = _.extend(respond.data.data, {
                  user: user[0],
                  patient: patient[0],
                  invoices: invoices[0] || null
                });
              }
            });
            this.$parent.currentData.forEach((e, i) => {
              if (e.data.id === this.$props.rowData.id) {
                this.$parent.currentData[i] = _.extend(respond.data.data, {
                  user: user[0],
                  patient: patient[0],
                  invoices: invoices[0] || null
                });
              }
            });
            this.$parent.notificationMessage = "Successfully updated!";
            this.$parent.notificationActive = true;
            this.$parent.selectedRowData = null;
            setTimeout(() => this.$parent.notificationActive = false, 3000);
            this.handleFlyoutClose();
          });
      });
    }
  },
  computed: {
    flyoutHeading() {
      return this.$props.rowData ? `Lab Order #${this.$props.rowData.id}` : '';
    },
    doctorName() {
      return this.$props.rowData ?
        `Dr. ${this.$root.$data.global.practitionerLookUp[Number(this.$props.rowData.practitioner_id)].attributes.name}, ND` :
        '';
    },
    patientName() {
      return this.$props.rowData ?
        `${this.$root.$data.global.patientLookUp[Number(this.$props.rowData.patient_id)].attributes.name}` :
        '';
    },
    paid() {
      return this.$props.rowData ? this.$props.rowData.paid : false;
    },
    validZip() {
      if (this.zip != '') {
        return this.zip.split('').filter(e => Number(e) == e).length > 0 && this.zip.length == 5;
      } else {
        return true;
      }
    },
    id() {
      return this.$props.rowData ? this.$props.rowData.id : '';
    },
    status() {
      return this.$props.rowData ? this.$props.rowData.completed_at : '';
    },
    shipmentCode() {
      return this.$props.rowData ? this.$props.rowData.shipment_code : '';
    },
    addressOne() {
      return this.$props.rowData ? this.$props.rowData.address_1 : '';
    },
    addressTwo() {
      return this.$props.rowData ? this.$props.rowData.address_2 : '';
    },
    city() {
      return this.$props.rowData ? this.$props.rowData.city : '';
    },
    state() {
      return this.$props.rowData ? this.$props.rowData.state : '';
    },
    zip() {
      return this.$props.rowData ? this.$props.rowData.zip : '';
    },
    patientUser() {
      return this.$props.rowData ? this.$props.rowData.patient_user_id : null;
    },
    stateList() {
      return ["State", "AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY"];
    },
    oldCard() {
      return this.$props.rowData ? this.$props.rowData.card : null;
    },
    price() {
      return this.$props.rowData ? this.$props.rowData.total_price.toFixed(2) : '';
    },
    invoicePrice() {
      return this.$props.rowData ? this.$props.rowData.invoice_paid : '';
    },
    samples() {
      return this.$props.rowData ? Object.keys(this.$props.rowData.samples) : [];
    },
    doctorList() {
      let data = {};
      let prop = this.$props.rowData;
      if (!prop) return [];
      let id = this.$props.rowData.practitioner_id;
      let lookUp = this.$root.$data.global.practitionerLookUp;
      data.name = `Dr. ${lookUp[Number(id)].attributes.name}`;
      data.id = lookUp[Number(id)].id;
      data.user_id = lookUp[Number(id)].attributes.user_id;
      let practitioners = this.$root.$data.global.practitioners;
      let arr = _.pull(practitioners, data);
      let array =  [data].concat(arr);
      return array;
    },
    statusList() {
      if (!this.$props.rowData) return "";
      return this.$props.rowData.completed_at;
    },
    testList() {
      if (!this.$props.rowData) return [];
      let results = this.$props.rowData && this.$props.rowData.test_list.length == 0 ? [{
        name: "No Lab Orders",
        cancel: true,
        status: ['No Order']
      }] : this.$props.rowData.test_list.filter(e => e.original_status !== 'canceled');
      return results;
    },
    patientTestList() {
      if (!this.$props.rowData) return {};
      let obj = {};
      this.$props.rowData.test_list.forEach(e => {
        obj[e.name] = e.test_id;
      });
      let objs = _.map(this.$root.$data.labTests, e => {
        e.patient = obj[e.attributes.name] ? true : false;
        e.checked = false;
        e.test_id = obj[e.attributes.name];
        return e;
      });
      let returns = {};
      objs.forEach(e => {
        returns[e.attributes.name] = e;
      });
      return returns;
    },
    latestCard() {
      return this.$root.$data.global.creditCards.slice(-1).pop();
    }
  }
};

</script>
