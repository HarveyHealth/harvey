<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'phone'" />
      <h2 v-text="title"></h2>
      <p v-html="subtext"></p>
    </div>
    <div class="signup-container signup-phone-container text-centered">
      <router-link class="signup-back-button" :to="{ name: 'practitioner', path: '/practitioner' }"><i class="fa fa-long-arrow-left"></i><span>Practitioner</span></router-link>

      <div class="phone-input-container" v-show="!$root.$data.signup.phonePending">
        <div class="signup-main-icon">
          <svg class="interstitial-icon icon-phone"><use xlink:href="#phone" /></svg>
        </div>
        <div class="input-wrap">
          <TheMask class="form-input form-input_text"
            name="phone_number"
            type="phone"
            v-model="phone"
            mask="(###) ###-####"
            placeholder="Mobile Number" />
          <span v-show="isInvalidNumber" class="error-text">Please supply a valid U.S. phone number.</span>
          <span v-show="isUserPatchError" class="error-text">There was an error processing your phone number. Please call us at <a href="tel:8006909989">800-690-9989</a> to speak with our Customer Support.</span>
        </div>
        <button class="button button--blue" style="width: 160px" :disabled="isPhoneProcessing" @click="processPhone(phone)">
          <span v-if="!isPhoneProcessing">Send Text</span>
          <LoadingGraphic v-else-if="isPhoneProcessing" :size="12" />
          <i v-else-if="isComplete" class="fa fa-check"></i>
        </button>
      </div>

      <div class="phone-input-container" v-show="$root.$data.signup.phonePending">
        <div class="signup-main-icon">
          <svg class="interstitial-icon icon-phone-sms"><use xlink:href="#phone-sms" /></svg>
        </div>

        <ConfirmInput :get-value="storeCode" :disabled="$root.$data.signup.codeConfirmed" :stored="code" />

        <button class="phone-process-button text-again" @click="handleNewSend" :disabled="$root.$data.signup.codeConfirmed">
          <i class="fa fa-repeat" aria-hidden="true"></i>
          Text Me Again
        </button>

        <button class="phone-process-button edit-phone" @click="newPhoneNumber">Re-Enter Phone</button>

        <p class="error-text" v-show="isInvalidCode">Invalid code entered.</p>
        <button class="button button--blue phone-confirm-button" style="width: 160px" :disabled="isPhoneConfirming" @click="processConfirmation(code)">
          <span v-if="$root.$data.signup.codeConfirmed"><i class="fa fa-check"></i><span class="button-text">Continue</span></span>
          <span v-else-if="!isPhoneConfirming">Confirm Code</span>
          <LoadingGraphic v-else :size="12" />
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import ConfirmInput from '../../../commons/ConfirmInput.vue';
import LoadingGraphic from '../../../commons/LoadingGraphic.vue';
import StagesNav from '../util/StagesNav.vue';
import { TheMask } from 'vue-the-mask';

export default {
  name: 'phone',
  components: {
    ConfirmInput,
    LoadingGraphic,
    StagesNav,
    TheMask,
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
      isInvalidCode: false,
      isInvalidNumber: false,
      phone: Laravel.user.phone || this.$root.$data.signup.phone || '',
      isPhoneConfirming: false,
      isPhoneProcessing: false,
      isUserPatchError: false,
    }
  },
  computed: {
    title() {
      return this.$root.$data.signup.phonePending
        ? 'Enter Confirmation Code'
        : 'Validate Phone Number';
    },
    subtext() {
      return this.$root.$data.signup.phonePending
        ? 'Please enter the confirmation code that was just sent to you via text message. You can click "Text Me Again" if you didn&rsquo;t receive it.'
        : 'Our doctors require a valid phone number on file for every patient. We will also send you text reminders before each appointment containing a link to your video conference meeting room.';
    },
    confirmInputComponent() {
      return this.$children.filter(child => {
        return child.hasOwnProperty('distribute');
      })[0];
    }
  },
  methods: {
    newPhoneNumber() {
      this.isPhoneProcessing = false;
      this.code = '';
      this.$root.$data.signup.phonePending = false;
      this.$root.$data.signup.codeConfirmed = false;
      this.$root.$data.signup.code = '';
    },
    storeCode(value) {
      this.code = value;
    },
    processConfirmation(code) {
      this.isInvalidCode = false;

      if (this.$root.$data.signup.codeConfirmed) {
        this.$router.push({ name: 'schedule', path: '/schedule' });
      }

      if (code.length < 5) {
        this.isInvalidCode = true;
      } else {
        this.isPhoneConfirming = true;
        // Send code off to the Twilio API
        axios.get(`/api/v1/users/${Laravel.user.id}/phone/verify?code=${code}`).then(response => {
          if (response.data.verified) {
            this.$root.$data.signup.codeConfirmed = true;
            this.$root.$data.signup.code = this.code;
            this.isPhoneConfirming = false;
            setTimeout(() => {
              this.$root.$data.signup.phoneConfirmed = true;
              this.$router.push({ name: 'schedule', path: '/schedule' });
            }, 500);
          } else {
            this.setInvalidCode();
          }
        }).catch(error => {
          Object.keys(this.confirmInputComponent.$refs).forEach(i => {
            this.confirmInputComponent.$refs[i].value = '';
          })
          this.setInvalidCode();
        })
      }

    },
    processPhone(number) {
      this.isInvalidNumber = false;
      const validNumber = (/\d{10}/).test(number);
      if (!validNumber) {
        this.isInvalidNumber = true;
        return;
      }

      this.isPhoneProcessing = true;
      this.isUserPatchError = false;
      this.$root.$data.signup.phone = number;

      // If a user returning to the flow already has a number stored and
      // they did not change it, just send the confirmation code again
      if (Laravel.user.phone === number) {
        setTimeout(this.sendConfirmation, 400);
      } else {
      // Else, patch the user's phone which triggers the code confirmation send
        axios.patch(`/api/v1/users/${Laravel.user.id}`, { phone: number }).then(response => {
          this.$root.$data.signup.phonePending = true;
          Laravel.user.phone = number;
          Vue.nextTick(() => document.querySelector('.phone-confirm-input-wrapper input').focus());

          // track the number patch
          if(this.$root.shouldTrack()) {
            // collect response information
            const userData = response.data.data.attributes;
            const userId = response.data.data.id || '';

            // Segment Identify update
            analytics.identify(userId, {
              phone: number,
            });
          }
        }).catch(error => {
          this.isUserPatchError = true;
          this.isPhoneProcessing = false;
        });
      }
    },
    handleNewSend() {
      Object.keys(this.confirmInputComponent.$refs).forEach(i => {
        this.confirmInputComponent.$refs[i].value = '';
      })
      this.sendConfirmation();
    },
    sendConfirmation() {
      axios.post(`api/v1/users/${Laravel.user.id}/phone/sendverificationcode`);
      this.isInvalidCode = false;
      this.$root.$data.signup.phonePending = true;
      Vue.nextTick(() => document.querySelector('.phone-confirm-input-wrapper input').focus());
    },
    setInvalidCode() {
      this.isPhoneConfirming = false;
      this.isInvalidCode = true;
    },
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('phone');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);

    if(this.$root.shouldTrack()) {
      analytics.page('Phone');
    }
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
