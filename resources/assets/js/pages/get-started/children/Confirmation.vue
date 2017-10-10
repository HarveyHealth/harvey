<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="vertical-center">
      <div class="signup-stage-instructions color-white">
        <StagesNav :current="'confirmation'" />
        <h2 v-if="$root.isSignupBookingAllowed" class="heading-1 color-white">Final Confirmation</h2>
        <h2 v-else class="heading-1 color-white">Oops, please go back.</h2>
      </div>
      <div class="signup-container small router">
        <router-link class="signup-back-button" :to="{ name: 'payment', path: '/payment' }">
          <i class="fa fa-long-arrow-left"></i>
          <span class="font-sm">Payment</span>
        </router-link>
        <div class="signup-main-icon">
          <svg v-if="$root.isSignupBookingAllowed" class="interstitial-icon icon-rocket"><use xlink:href="#clipboard"/></svg>
          <svg v-else class="interstitial-icon icon-error"><use xlink:href="#error"/></svg>
        </div>
        <p v-if="$root.isSignupBookingAllowed">You are about to book a video consultation with Dr. {{ doctor }} on {{ dateDisplay }} at {{ timeDisplay }}.<br/><br/>{{ paymentStatement }}. Our of respect for our doctors, we charge a $10 fee for <em>no shows</em> or cancelations within 6 hours of your appointment.</p>
        <p v-else>Looks like we're missing a few things from you before we can schedule your consultation. If you're having trouble, please give us a call at <a href="tel:8006909989">800-690-9989</a>, or click the chat button at the bottom corner of the page.</p>
        <ul v-show="!$root.isSignupBookingAllowed" class="error-list">
          <li class="copy-error" v-show="!$root.$data.signup.data.practitioner_id">Please select your practitioner.</li>
          <li class="copy-error" v-show="!$root.$data.signup.data.appointment_at">Please select an appointment date and time.</li>
          <li class="copy-error" v-show="!$root.$data.signup.phoneConfirmed">Please confirm your phone number.</li>
          <li class="copy-error" v-show="!$root.$data.signup.billingConfirmed">Please enter your payment method.</li>
        </ul>
        <button class="button button--blue" v-if="$root.isSignupBookingAllowed" :disabled="isProcessing" @click="confirmSignup" :style="{ width: '200px'}">
          <span v-if="!isProcessing">Book Appointment</span>
          <ClipLoader v-else-if="isProcessing" :color="'#ffffff'" :size="'12px'" />
        </button>
      </div>
      <Overlay :active="showModal"/>
      <Modal
        :active="showModal"
        :container-class="'appointment-modal'"
        :on-close="() => showModal = false"
        class="modal-wrapper"
      >
        <div class="card-content-wrap">
          <div class="inline-centered">
            <h1 class="header-xlarge">Booking Conflict</h1>
            <p>We&rsquo;re sorry, it looks like that date and time is no longer available. Please try another time. For general questions, please give us a call at <a href="tel:8006909989">800-690-9989</a>, or click the chat button at the bottom corner of the page.</p>
            <button class="button button--cancel" @click="handleNewAvailability">Back to Schedule</button>
          </div>
        </div>
      </Modal>
    </div>
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
        'pad-md': true,
        'flex-wrapper': true,
        'height-100': true,
        'justify-center': true
      },
      date: this.$root.$data.signup.data.appointment_at,
      doctor: `${this.$root.$data.signup.practitionerName}, ND`,
      paymentStatement: `Your consultation will cost $150, which will be charged to your ${this.$root.$data.signup.cardBrand || 'card'} after the consultation`,
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
  },
  filters: {
    getState,
  },
  methods: {
    confirmSignup() {
      this.isProcessing = true;
      if (!this.$root.$data.signup.data.discount_code) {
        delete this.$root.$data.signup.data.discount_code;
      }
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
