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
    <div class="input__container">
        <label class="input__label" for="patient_name">tests</label>
    </div>
    <div>
        <div class="inline-centered">
            <button class="button"
            @click="nextStep()"
            :disabled="!selectedClient || !selectedDoctor">Save &amp; Continue</button>
        </div>
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
      <div class="input__container">
          <label class="input__label" for="patient_name">master tracking</label>
      </div>
      <div>
          <div class="inline-centered">
              <button class="button"
              @click="prevStep()"
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
      micronutrient: ''
    }
  },
  methods: {
    nextStep() {
      this.step++
    },
    prevStep() {
      this.step--
    },
    updateClient(e) {
        this.selectedClient = e.target.children[e.target.selectedIndex].dataset.id;
    },
    updateDoctor(e) {
        this.selectedDoctor = e.target.children[e.target.selectedIndex].dataset.id;
    },
    handleFlyoutClose() {
      this.$parent.addFlyoutActive = !this.$parent.addFlyoutActive
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
    }
  }
}
</script>
