<template>
  <Flyout
    :active="$parent.addFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
  >
  <div>
      <div class="input__container">
          <label class="input__label" for="patient_name">client</label>
          <span class="custom-select">
              <select @change="updateClient($event)">
                  <option v-for="client in clientList" :data-id="client.id">{{ client.name }}</option>
              </select>
          </span>
      </div>
      <div class="input__container">
          <label class="input__label" for="patient_name">doctor</label>
          <span class="custom-select">
              <select @change="updateDoctor($event)">
                  <option v-for="doctor in doctorList" :data-id="doctor.id">{{ doctor.name }}</option>
              </select>
          </span>
      </div>
      <div>
            <label class="input__label" for="patient_name">tests</label>
            <span v-for="tests in testNameList" :class="{highlightCheckbox: tests.checked}" class="fullscreen-left">
                <input :checked="tests.checked" @click="updateTestSelection($event, tests)" class="form-radio" type="checkbox">
                <label :class="{highlightTextColor: tests.checked}" class="radio--text">{{ tests.attributes.name }}</label>
            </span>
      </div>
        <div class="inline-centered">
            <button class="button flyout-btn"
            @click="nextStep()"
            :disabled="!selectedClient || !selectedDoctor || selectedTests.length == 0">Create Recommendation</button>
        </div>
  </div>
    <Modal :active="$parent.addActiveModal" :onClose="modalClose">
      <div class="inline-centered">
        <h1>Create Lab Order</h1>
        <p>Are you sure you want to create a new lab order recommedation for client <b>{{ selectedClientName }}</b> and doctor <b>{{ selectedDoctorName }}</b>?</p>
        <ul style="text-align: left; margin-left: 125px; padding-bottom: 5px;">
          <li v-for="test in selectedTests">{{ test.attributes.name }}</li>
        </ul>
        <div class="inline-centered">
            <button @click="createLabOrder" class="button">Yes, Confirm</button>
        </div>
      </div>
    </Modal>
  </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue'
import Modal from '../../../commons/Modal.vue'
import SelectOptions from '../../../commons/SelectOptions.vue'
import axios from 'axios'
import _ from 'lodash'
export default {
  props: ['reset', 'labTests'],
  name: 'AddLabOrders',
  components: {
    Flyout,
    Modal,
    SelectOptions
  },
  data() {
    return {
      activeModal: false,
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
      shippingCodes: {},
      prevDoctor: '',
      prevClient: '',
      testNamesInList: [],
      selectedClientName: '',
      selectedDoctorName: '',
      doctorList:this.$root.$data.global.selfPractitionerInfo != null ? [this.$root.$data.global.selfPractitionerInfo] : [''].concat(this.$root.$data.global.practitioners),
      clientList: [''].concat(this.$root.$data.global.patients)
    }
  },
  methods: {
    nextStep() {
      this.step++
      let patients = _.pull(this.$root.$data.global.patients, {id: this.selectedClient})
      let patientsFind = _.find(this.$root.$data.global.patients, {id: this.selectedClient})
      this.clientList = [patientsFind].concat(patients)
      if (window.Laravel.user.user_type == 'admin') {
        let doctors = _.pull(this.$root.$data.global.practitioners, {id: this.selectedDoctor})
        let doctorsFind = _.find(this.$root.$data.global.practitioners, {id: this.selectedDoctor})
        this.doctorList = [doctorsFind].concat(doctors)
      }
    },
    prevStep() {
      this.step--
    },
    modalClose() {
      this.$parent.addActiveModal = false
    },
    openModal() {
      this.$parent.addActiveModal = true
    },
    updateTestSelection(e, obj) {
      this.testNameList[obj.id - 1].checked = !this.testNameList[obj.id - 1].checked
      if (this.testNameList[obj.id - 1].checked) {
        this.selectedTests.push(obj)
      } else {
        _.pull(this.selectedTests, obj)
      }
    },
    updateClient(e) {
        this.selectedClient = e.target.children[e.target.selectedIndex].dataset.id;
        this.selectedClientName = e.target.value.name;
    },
    updateState(e) {
      this.state = e.target.value
    },
    updateDoctor(e) {
        this.selectedDoctor = e.target.children[e.target.selectedIndex].dataset.id;
        this.selectedDoctorName = e.target.value.name;
    },
    handleFlyoutClose() {
      this.$parent.addFlyoutActive = !this.$parent.addFlyoutActive
      this.$parent.addActiveModal = false
      this.step = 1
    },
    createLabOrder() {
        this.selectedTests.map(e => {
          e.shipping_code = this.shippingCodes[e.id]
          return e
        })
        let data = null
        if (this.address2.length > 0) {
          data = {
              practitioner_id: this.selectedDoctor,
              patient_id: this.selectedClient,
              shipment_code: this.masterTracking,
              address_1: this.address1,
              address_2: this.address2,
              city: this.city,
              state: this.state,
              zip: Number(this.zip)
            }
          } else {
            data = {
              practitioner_id: this.selectedDoctor,
              patient_id: this.selectedClient,
              shipment_code: this.masterTracking,
              address_1: this.address1,
              city: this.city,
              state: this.state,
              zip: Number(this.zip)
            }
          }
        axios.post(`${this.$root.$data.apiUrl}/lab/orders`, data)
        .then(response => {
          this.selectedTests.forEach((e, i)=> {
            axios.post(`${this.$root.$data.apiUrl}/lab/tests`, {
                lab_order_id: Number(response.data.data.id),
                sku_id: Number(e.id),
                shipment_code: this.shippingCodes[e.id]
              })
          })

          this.selectedClient = ''
          this.step = 1
          this.masterTracking = ''
          this.address1 = ''
          this.selectedDoctor = ''
          this.address2 = ''
          this.city = ''
          this.zip = ''
          this.state = ''
          this.selectedTests = []
          this.shippingCodes = {}
          this.prevDoctor = ''
          this.prevClient = ''
          this.doctorList = [''].concat(this.$root.$data.global.practitioners)
          this.clientList = [''].concat(this.$root.$data.global.patients)
          Object.values(this.$props.labTests).map(e => e.checked = false)

          this.$parent.notificationMessage = "Successfully added!";
          this.$parent.notificationActive = true;
          setTimeout(() => this.$parent.notificationActive = false, 3000);
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
            })
        this.handleFlyoutClose();
        this.modalClose();
    }
  },
  computed: {
    flyoutHeading() {
      if (this.step == 1) return "New Lab Order"
      if (this.step == 2) return "Enter Tracking #s"
    },
    stateList() {
      return ["Enter State", "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"]
    },
    testNameList() {
      return Object.values(this.$props.labTests).sort((a,b) => a.id - b.id)
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
      if (val) {
        return Object.values(this.$props.labTests).sort((a,b) => a.id - b.id)
      }
    }
  },
  mounted() {
    let selfPractitioner = this.$root.$data.global.selfPractitionerInfo
    if (selfPractitioner) {
      this.selectedDoctor = selfPractitioner.id
      this.selectedDoctorName = selfPractitioner.name
    }
  }
}
</script>
