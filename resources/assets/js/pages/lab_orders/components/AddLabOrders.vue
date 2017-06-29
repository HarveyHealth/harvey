<template>
  <Flyout
    :active="$parent.addFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
    :back="step == 2 ? prevStep : null"
  >
  <div v-if="step == 1">
    <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
      <div class="input__container">
          <label class="input__label" for="patient_name">client</label>
          <span class="custom-select">
              <select @change="updateClient($event)">
                  <option v-for="client in clientList" :data-id="client.user_id">{{ client.name }}</option>
              </select>
          </span>
      </div>
      <div class="input__container">
          <label class="input__label" for="patient_name">doctor</label>
          <span class="custom-select">
              <select @change="updateDoctor($event)">
                  <option v-for="doctor in doctorList" :data-id="doctor.user_id">{{ doctor.name }}</option>
              </select>
          </span>
      </div>
    </div>
    <div>
          <label class="input__label" for="patient_name">tests</label>
          <span v-for="tests in testNameList" class="fullscreen-left">
              <input :checked="tests.attributes.checked" @click="updateTestSelection($event, tests)" class="form-radio" type="radio"> 
              <label class="radio--text">{{ tests.attributes.name }}</label>
          </span>
    </div>
        <div class="inline-centered">
            <button class="button flyout-btn"
            @click="nextStep()"
            :disabled="!selectedClient || !selectedDoctor || selectedTests.length == 0">Save &amp; Continue</button>
        </div>
  </div>
  <div v-if="step == 2">
    <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
      <div v-for="test in selectedTests">
          <div class="input__container">
              <label class="input__label" for="patient_name">{{ test.attributes.name }}</label>
              <input v-model="shippingCodes[test.id]" class="input--text" type="text">
          </div>
        </div>
      </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
        <div class="input__container">
            <label class="input__label" for="patient_name">master tracking</label>
            <input v-model="masterTracking" class="input--text" type="text">
        </div>
      </div>
      <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
        <div class="input__container">
            <label class="input__label" for="patient_name">mailing address</label>
            <input placeholder="Enter address 1" v-model="address1" class="input--text" type="text">
            <input placeholder="Enter address 2" v-model="address2" class="input--text" type="text">
            <input placeholder="Enter city" v-model="city" class="input--text" type="text">
            <input placeholder="Enter zip" v-model="zip" class="input--text" type="text" style="width: 50%; float: left; margin-right: 5%;">
            <span class="custom-select" style="width: 45%; float:left;">
                <select @change="updateState($event)">
                    <option v-for="state in stateList" :data-id="state">{{ state }}</option>
                </select>
            </span>
            <label v-if="!validZip" class="input__label" style="color: #EDA1A6; margin-top: 70px; text-align: center;">Please enter a valid zip code</label>
          </div>
        </div> 
        <div>
            <div class="inline-centered">
                <button class="button"
                @click="createLabOrder()"
                :disabled="!validZip || selectedDoctor.length == 0 || selectedClient.length == 0  || masterTracking.length == 0 || address1.length == 0 || city.length == 0 || zip.length == 0 || state.length == 0 "
                >Mark as Shipped</button>
            </div>
        </div>
      </div>
    </div>
  </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue'
import SelectOptions from '../../../commons/SelectOptions.vue'
import axios from 'axios'
import _ from 'lodash'
export default {
  name: 'AddLabOrders',
  components: {
    Flyout,
    SelectOptions
  },
  data() {
    return {
      selectedDoctor: '',
      selectedClient: '',
      step: 1,
      masterTracking: '',
      address1: '',
      address2: '',
      city: '',
      zip: '',
      state: '',
      selectedTests: [],
      shippingCodes: {}
    }
  },
  methods: {
    nextStep() {
      this.step++
    },
    prevStep() {
      this.step--
    },
    updateTestSelection(e, obj) {
      this.testNameList[obj.id - 1].attributes.checked = !this.testNameList[obj.id - 1].attributes.checked
      if (this.testNameList[obj.id - 1].attributes.checked) {
        this.selectedTests.push(obj)
      } else {
        _.pull(this.selectedTests, obj)
      }
    },
    updateClient(e) {
        this.selectedClient = e.target.children[e.target.selectedIndex].dataset.id;
    },
    updateState(e) {
      this.state = e.target.value
    },
    updateDoctor(e) {
        this.selectedDoctor = e.target.children[e.target.selectedIndex].dataset.id;
    },
    handleFlyoutClose() {
      this.$parent.addFlyoutActive = !this.$parent.addFlyoutActive
      this.step = 1
    },
    createLabOrder() {
        this.selectedTests.map(e => {
          e.shipping_code = this.shippingCodes[e.id]
          return e
        })
        axios.post(`${this.$root.$data.apiUrl}/lab/orders`, {
          practitioner_id: this.selectedDoctor,
          patient_id: this.selectedClient,
          shipment_code: this.masterTracking,
          address_1: this.address1,
          address_2: this.address2,
          city: this.city,
          state: this.state,
          zip: this.zip
        })
        .then(response => {
          this.selectedTests.forEach(e => {
            axios.post(`${this.$root.$data.apiUrl}/lab/tests`, {
                lab_order_id: response.data.data.id,
                sku_id: e.id,
                shipment_code: e.shipment_code
              })
          })
          this.$parent.notificationMessage = "Successfully added!";
          this.$parent.notificationActive = true;
          setTimeout(() => this.$parent.notificationActive = false, 3000);
        })
        this.handleFlyoutClose()
    }
  },
  computed: {
    doctorList() {
      return [''].concat(this.$root.$data.global.practitioners)
    },
    clientList() {
      return [''].concat(this.$root.$data.global.patients)
    },
    flyoutHeading() {
      if (this.step == 1) return "New Lab Order"
      if (this.step == 2) return "Enter Tracking #s"
    },
    stateList() {
      return ["Enter State", "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"]
    },
    testNameList() {
      let labArray = Object.values(this.$root.$data.labTests)
      return labArray
    },
    validZip() {
      if (this.zip != '') {
        return this.zip.split('').filter(e => Number(e) == e).length > 0 && this.zip.length == 5
      } else {
        return true
      }
    }
  },
  watch: {
    testNameList(val) {
      return Object.values(this.$root.$data.labTests)
    }
  }
}
</script>
