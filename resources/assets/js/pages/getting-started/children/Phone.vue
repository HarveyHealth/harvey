<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'phone'" />
      <h2 v-text="title"></h2>
      <p v-html="subtext"></p>
    </div>
    <div class="signup-container signup-phone-container text-centered">
      <router-link class="signup-back-button" :to="{ name: 'practitioner', path: '/practitioner' }"><i class="fa fa-arrow-left"></i> Practitioner</router-link>

      <div class="phone-input-container" v-if="!$root.$data.signup.phonePending">
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
            v-validate="{ required: true, regex: /\(\d{3}\) \d{3}-\d{4}/ }"
            data-vv-validate-on="blur"
          />

          <span v-show="errors.has('phone_number')" class="error-text">Please supply a valid U.S. phone number.</span>
        </div>
        <button class="button button--blue" style="width: 160px" :disabled="phoneProcessing" @click="processPhone(phone)">
          <span v-if="!phoneProcessing">Send Text</span>
          <LoadingBubbles v-else-if="phoneProcessing" :style="{ width: '12px', fill: 'white' }" />
          <i v-else-if="isComplete" class="fa fa-check"></i>
        </button>
      </div>

      <div class="phone-input-container" v-else-if="$root.$data.signup.phonePending">
        <div class="signup-main-icon">
          <svg class="interstitial-icon icon-phone-sms"><use xlink:href="#phone-sms" /></svg>
        </div>

        <ConfirmInput :get-value="storeCode" :disabled="$root.$data.signup.codeConfirmed" :stored="code" />

        <button class="phone-process-button" @click="sendConfirmation" :disabled="$root.$data.signup.codeConfirmed">Text Me Again</button>

        <button class="phone-process-button" @click="newPhoneNumber">Edit Phone Number</button>

        <p class="error-text" v-show="invalidCode">Invalid code entered.</p>
        <button class="button button--blue phone-confirm-button" style="width: 160px" :disabled="phoneConfirming" @click="processConfirmation(code)">
          <span v-if="$root.$data.signup.codeConfirmed"><i class="fa fa-check"></i><span class="button-text">Continue</span></span>
          <span v-else-if="!phoneConfirming">Confirm Code</span>
          <LoadingBubbles v-else :style="{ width: '12px', fill: 'white' }" />
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import ConfirmInput from '../util/ConfirmInput.vue';
import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'phone',
  components: {
    ConfirmInput,
    LoadingBubbles,
    StagesNav,
  },
  data() {
    return {
      code: this.$root.$data.signup.code || '',
      codeDigits: 5,
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      invalidCode: false,
      phone: this.$root.$data.signup.phone || '',
      phoneConfirming: false,
      phoneProcessing: false,
    }
  },
  computed: {
    title() {
      return this.$root.$data.signup.phonePending
        ? 'Enter confirmation code...'
        : 'Provide phone number...';
    },
    subtext() {
      return this.$root.$data.signup.phonePending
        ? 'Please enter the Harvey confirmation code that was just sent to you via text message. We can send it again if you didn&rsquo;t receive it.'
        : 'Your doctor will need to call you on a <strong>mobile</strong> phone number with text message capabilities. Please validate your mobile number. We will only call you with issues concerning your account.';
    },
    confirmInputComponent() {
      return this.$children.filter(child => {
        return child.hasOwnProperty('distribute');
      })[0];
    }
  },
  methods: {
    newPhoneNumber() {
      this.phoneProcessing = false;
      this.phone = '';
      this.code = '';
      this.$root.$data.signup.phone = '';
      this.$root.$data.signup.phonePending = false;
      this.$root.$data.signup.codeConfirmed = false;
      this.$root.$data.signup.code = '';
    },
    storeCode(value) {
      this.code = value;
    },
    processConfirmation(code) {
      this.invalidCode = false;

      if (this.$root.$data.signup.codeConfirmed) {
        this.$router.push({ name: 'schedule', path: '/schedule' });
      }

      if (code.length < 5) {
        this.invalidCode = true;
      } else {
        this.phoneConfirming = true;
        // Send code off to the Twilio API
        axios.get(`/api/v1/users/${Laravel.user.id}/phone/verify?code=${code}`).then(response => {
          if (response.data.verified) {
            this.$root.$data.signup.codeConfirmed = true;
            this.$root.$data.signup.code = this.code;
            this.phoneConfirming = false;
            setTimeout(() => {
              this.$router.push({ name: 'schedule', path: '/schedule' });
            }, 500);
          } else {
            this.phoneConfirming = false;
            this.invalidCode = true;
          }
        }).catch(error => {
          Object.keys(this.confirmInputComponent.$refs).forEach(i => {
            this.confirmInputComponent.$refs[i].value = '';
          })
          this.phoneConfirming = false;
          this.invalidCode = true;
        })
      }

    },
    processPhone(number) {
      this.$validator.validateAll().then(() => {
        this.phoneProcessing = true;
        this.$root.$data.signup.phone = number;
        // User PATCH triggers the Twilio code send on the BE
        axios.patch(`/api/v1/users/${Laravel.user.id}`, { phone: number }).then(response => {
          this.$root.$data.signup.phonePending = true;
          Vue.nextTick(() => document.querySelector('.phone-confirm-input-wrapper input').focus());
        });
      })
      .catch(error => {

      });
    },
    sendConfirmation(number) {
      Object.keys(this.confirmInputComponent.$refs).forEach(i => {
        this.confirmInputComponent.$refs[i].value = '';
      })
      console.log(`Sending number (${this.$root.$data.signup.phone} to Twilio API`);
    },
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
    // this.$root.$data.signup.phonePending = true;
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('phone');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
