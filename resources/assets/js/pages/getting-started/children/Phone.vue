<template>
  <div :class="containerClasses">
    <div class="signup-stage-instructions">
      <StagesNav :current="'phone'" />
      <h2>Provide phone number...</h2>
      <p>Your doctor will need to call you on a <strong>mobile</strong> phone number with text message capabilities. Please validate your mobile number. We will only call you with issues concerning your account.</p>
    </div>
    <div class="signup-container signup-interstitial-container text-centered">
      <router-link class="signup-back-button" :to="{ name: 'practitioner', path: '/practitioner' }"><i class="fa fa-arrow-left"></i> Practitioner</router-link>
      <div class="signup-main-icon">
        <svg class="interstitial-icon icon-phone"><use xlink:href="#phone" /></svg>
      </div>
      <div class="input-wrap">
        <input class="form-input form-input_text"
          name="phone_number"
          type="phone"
          placeholder="Mobile Number"
          v-phonemask="phone"
          v-on:click="trackingPhoneNumber"
          v-on:change="$root.$data.signup.phone = phone"
          v-validate="{ required: true, regex: /\(\d{3}\) \d{3}-\d{4}/ }"
          data-vv-validate-on="blur"
        />

        <span v-show="errors.has('phone_number')" class="error-text">Please supply a valid U.S. phone number.</span>
      </div>
      <button class="button button--blue">Send Text</button>
    </div>
  </div>
</template>

<script>
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'phone',
  components: {
    StagesNav,
  },
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      phone: this.$root.$data.signup.phone || ''
    }
  },
  methods: {
    trackingPhoneNumber() {
      if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
        ga('send', {
          hitType: "event",
          eventCategory: "clicks",
          eventAction: "Click Phone Number",
          eventLabel: null,
            eventValue: 50,
            hitCallback: null,
            userId: null
        });
      }
    }
  },
  mounted () {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
