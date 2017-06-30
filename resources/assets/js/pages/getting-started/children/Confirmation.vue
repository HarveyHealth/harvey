<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'confirmation'" />
      <h2>Final confirmation...</h2>
      <p>Please confirm that the information below is correct. If it is not, click back to edit any previous information.</p>
    </div>
    <div class="signup-container signup-interstitial-container">
      <router-link class="signup-back-button" :to="{ name: 'schedule', path: '/schedule' }"><i class="fa fa-arrow-left"></i> Schedule</router-link>
      <div class="signup-main-icon">
        <svg class="interstitial-icon icon-rocket"><use xlink:href="#clipboard" /></svg>
      </div>
      <p>You are about to book a ~60 minute consultation appointment with <strong>Dr. {{ this.doctor }}</strong>, a licensed Naturopathic Doctor from {{ this.state }}.</p>
      <p>{{ firstName }} will call you on <strong>{{ dateDisplay }}</strong> at <strong>{{ timeDisplay }}</strong> at <strong>{{ phoneDisplay }}</strong>. The cost for this consultation will be $150, which will be charged to your AMEX on file after the first appointment.</p>
      <p>Let&rsquo;s start a journey together.</p>
      <button class="button button--blue" style="width: 180px" :disabled="processing" @click="confirmSignup">
        <span v-if="!processing">Confirm Booking</span>
        <LoadingBubbles v-else-if="processing" :style="{ width: '12px', fill: 'white' }" />
      </button>
    </div>
  </div>
</template>

<script>
import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
import StagesNav from '../util/StagesNav.vue';
import moment from 'moment';

export default {
  name: 'confirmation',
  components: {
    LoadingBubbles,
    StagesNav,
  },
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      date: this.$root.$data.signup.data.appointment_at,
      doctor: `${this.$root.$data.signup.practitionerName}, N.D`,
      phone: this.$root.$data.signup.phone,
      processing: false,
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
    }
  },
  methods: {
    confirmSignup() {
      this.processing = true;
      axios.post('/api/v1/appointments', this.$root.$data.signup.data).then(response => {
        this.processing = false;
        this.$router.push({ name: 'success', path: 'success' });
      });
    }
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('confirmation');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }

}
</script>
