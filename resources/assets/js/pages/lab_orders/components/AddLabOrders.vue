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
              <input :checked="tests.checked" @click="updateTestSelection($event, tests.id)" class="form-radio" type="radio"> 
              <label class="radio--text">{{ tests.name }}</label>
          </span>
    </div>
        <div class="inline-centered">
            <button class="button flyout-btn"
            @click="nextStep()"
            :disabled="!selectedClient || !selectedDoctor">Save &amp; Continue</button>
        </div>
  </div>
  <div v-if="step == 2">
    <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
        <div class="input__container">
            <label class="input__label" for="patient_name">micronutrient</label>
            <input v-model="micronutrient" class="input--text" type="text">
        </div>
        <div class="input__container">
            <label class="input__label" for="patient_name">hormones</label>
            <input v-model="hormones" class="input--text" type="text">
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
                <select @change="updateDoctor($event)">
                    <option v-for="state in stateList" :data-id="state">{{ state }}</option>
                </select>
            </span>
          </div>
        </div> 
        <div>
            <div class="inline-centered">
                <button class="button"
                @click="createLabOrder()"
                :disabled="!selectedDoctor || !selectedClient || !micronutrient || !masterTracking || !address1 || !address2 || !city || !zip || !state"
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
      hormones: '',
      micronutrient: '',
      masterTracking: '',
      address1: '',
      address2: '',
      city: '',
      zip: '',
      state: '',
      selectedTests: [],
      testNameList: [
        {
          name: "Blood Test",
          id: 1,
          checked: false
        },
        {
          name: "Blood Test",
          id: 2,
          checked: false
        },
        {
          name: "Blood Test",
          id: 3,
          checked: false
        },
        {
          name: "Blood Test",
          id: 4,
          checked: false
        },
        {
          name: "Blood Test",
          id: 5,
          checked: false
        },
        {
          name: "Blood Test",
          id: 6,
          checked: false
        },
        {
          name: "Blood Test",
          id: 7,
          checked: false
        },
        {
          name: "Blood Test",
          id: 8,
          checked: false
        },
        {
          name: "Blood Test",
          id: 9,
          checked: false
        }
      ]
    }
  },
  methods: {
    nextStep() {
      this.step++
    },
    prevStep() {
      this.step--
    },
    updateTestSelection(e, id) {
      this.testNameList[id - 1].checked = !this.testNameList[id - 1].checked
      console.log(this.testNameList)
      if (this.testNameList[id - 1].checked) {
        this.selectedTests.push(id)
      } else {
        _.pull(this.selectedTests, id)
      }
    },
    updateClient(e) {
        this.selectedClient = e.target.children[e.target.selectedIndex].dataset.id;
    },
    updateDoctor(e) {
        this.selectedDoctor = e.target.children[e.target.selectedIndex].dataset.id;
    },
    handleFlyoutClose() {
      this.$parent.addFlyoutActive = !this.$parent.addFlyoutActive
      this.step = 1
    },
    createLabOrder() {
        axios.post(`${this.$root.$data.apiUrl}/lab/orders/${this.$props.rowData.id}`, {
          practitioner_id: this.selectedDoctor,
          patient_id: this.selectedClient,
          status: "shipped",
          shipment_code: this.masterTracking
        })
        .then(response => {
            this.handleFlyoutClose()
            // Add to data table
        })
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
    }
  }
}
</script>
