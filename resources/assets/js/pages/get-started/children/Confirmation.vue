<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'confirmation'" />
      <h2 class="heading-3">Final Confirmation</h2>
    </div>
    <div class="signup-container signup-interstitial-container">
      <router-link class="signup-back-button" :to="{ name: 'payment', path: '/payment' }">
        <i class="fa fa-long-arrow-left"></i>
        <span class="font-sm">Payment</span>
      </router-link>
      <div class="signup-main-icon">
        <svg class="interstitial-icon icon-rocket"><use xlink:href="#clipboard" /></svg>
      </div>

      <p v-if="isBookingAllowed">By clicking below, you agree to a 60-minute consultation with Dr. {{ doctor }}. Your video chat with {{ firstName }} will be on {{ dateDisplay }} at {{ timeDisplay }}. {{ paymentStatement }}</p>

      <div v-show="!isBookingAllowed">
        <p class="copy-error" v-show="!$root.$data.signup.data.practitioner_id">You must select a practitioner</p>
        <p class="copy-error" v-show="!$root.$data.signup.data.appointment_at">Appointment time has not been scheduled</p>
        <p class="copy-error" v-show="!$root.$data.signup.phoneConfirmed">Please confirm phone number before booking an appointment</p>
        <p class="copy-error" v-show="!$root.$data.signup.billingConfirmed">Please confirm billing before booking an appointment</p>
      </div>

      <button class="button button--blue" v-if="isBookingAllowed" :disabled="isProcessing" @click="confirmSignup" :style="{ width: '200px'}">
        <span v-if="!isProcessing">Book Appointment</span>
        <ClipLoader v-else-if="isProcessing" :color="'#ffffff'" :size="'12px'" />
      </button>
    </div>

    <Overlay :active="showModal" />
    <Modal :active="showModal" :on-close="() => showModal = false">
      <p class="copy-error">We&rsquo;re sorry, it looks like that date and time was recently booked. Please take a look at other available times.</p>
      <button @click="handleNewAvailability" class="button button--blue" style="width: 200px; margin-top: 20px;">
        <span v-if="!isBackProcessing">Back to Schedule</span>
        <ClipLoader v-else-if="isBackProcessing" :color="'#ffffff'" :size="'12px'" />
      </button>
    </Modal>

  </div>
</template>

<script>
import getState from '../../../utils/methods/getState';
import moment from 'moment';
import transformAvailability from '../../../utils/methods/transformAvailability';

import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
import Modal from '../../../commons/Modal.vue';
import Overlay from '../../../commons/Overlay.vue';
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'confirmation',
  components: {
    ClipLoader,
    Modal,
    Overlay,
    StagesNav,
  },
  data() {
    return {
      isBackProcessing: false,
      isProcessing: false,
      cardBrand: this.$root.$data.signup.cardBrand,
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      date: this.$root.$data.signup.data.appointment_at,
      doctor: `${this.$root.$data.signup.practitionerName}, ND`,
      paymentStatement: `The cost for this consultation will be $150, which will be charged to your ${this.$root.$data.signup.cardBrand || 'card'} after the consultation is complete.`,
      phone: this.$root.$data.signup.phone || this.$root.$data.global.user.attributes.phone,
      showModal: false,
      state: this.$root.$data.signup.practitionerState
    }
  },
  computed: {
    dateDisplay() {
      return moment.utc(this.date).local().format('dddd, MMMM Do');
    },
    firstName() {
      return this.$root.$data.signup.practitionerName.replace(/ .*/, '');
    },
    timeDisplay() {
      return this.$root.addTimezone(moment.utc(this.date).local().format('h:mm a'));
    },
    phoneDisplay() {
      return this.phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    },
    isBookingAllowed() {
      return (this.$root.$data.signup.billingConfirmed &&
        this.$root.$data.signup.phoneConfirmed &&
        this.$root.$data.signup.data.appointment_at &&
        this.$root.$data.signup.data.practitioner_id)
    }
  },
  filters: {
    getState,
  },
  methods: {
    confirmSignup() {
      this.isProcessing = true;
      axios.post('/api/v1/appointments', this.$root.$data.signup.data).then(response => {
        this.$root.$data.signup.googleMeetLink = response.data.data.attributes.google_meet_link;
        window.onbeforeunload = null;
        this.isProcessing = false;
        App.Util.data.killStorage('zip_validation');
        this.$router.push({ name: 'success', path: 'success' });
      })
      .catch(error => {
        // 400 Bad request means the time was booked just before the signup user confirmed but after they
        // loaded availability for their selected practitioner.
        this.showModal = true;
      })
    },
    handleNewAvailability() {
      this.isBackProcessing = true;
      this.$root.getAvailability(this.$root.$data.signup.data.practitioner_id, response => {
        this.$root.$data.signup.availability = transformAvailability(response.data.meta.availability, Laravel.user.user_type);
        this.$root.$data.signup.selectedWeek = null;
        this.$root.$data.signup.selectedDay = null;
        this.$root.$data.signup.selectedTime = null;
        this.$root.$data.signup.selectedDate = null;
        this.isBackProcessing = false;
        this.$router.push({ name: 'schedule', path: '/schedule' });
      });
    },
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('confirmation');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);

    if(this.$root.shouldTrack()) {
      analytics.page('Confirmation');
    }
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }

}
</script>
