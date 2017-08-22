<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'payment'" />
      <h2 v-text="title"></h2>
      <p v-html="subtext"></p>

      <div class="credit-card" v-show="!$root.$data.signup.billingConfirmed"></div>
    </div>
    <div class="signup-container signup-phone-container text-centered">
      <router-link class="signup-back-button" :to="{ name: 'schedule', path: '/schedule' }">
        <i class="fa fa-long-arrow-left"></i><span>Schedule</span>
      </router-link>

      <div class="phone-input-container">
        <form id="credit-card-form" class="input-container cf">
          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray"
                  :disabled="isComplete" name="card_number" type="text" placeholder="Card Number" v-model="cardNumber" />
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray"
                  :disabled="isComplete" name="card_name" type="text" placeholder="Name on Card" v-model="cardName" />
          </div>
          <div>
            <div class="input-wrap input-half--sm">
              <input class="form-input form-input_text font-base font-darkest-gray"
                    :disabled="isComplete" name="card_expiration" type="text" placeholder="MM/YY" v-model="cardExpiration" />
            </div>
            <div class="input-wrap input-half--sm last">
              <input class="form-input form-input_text font-base font-darkest-gray"
                    :disabled="isComplete" name="card_cvc" type="text" placeholder="CVC" v-model="cardCvc" />
            </div>
          </div>
        </form>

        <p>We will not charge you. You can confirm your appointment date and time on the next page.</p>

        <p class="error-text" v-show="stripeError.length" v-html="stripeError"></p>

        <button class="button button--blue" style="width: 180px" :disabled="isProcessing || isComplete" @click="onSubmit($event)">
          <span v-if="!isProcessing && !isComplete">Save &amp; Continue</span>
          <LoadingGraphic v-else-if="isProcessing" :style="{ width: '12px', fill: 'white' }" />
          <i v-else-if="isComplete" class="fa fa-check"></i>
        </button>
        <button class="button button--cancel" v-show="isComplete" @click="resetCardData">Edit Card</button>
      </div>

    </div>
  </div>
</template>

<script>
import card from 'card';
import LoadingGraphic from '../../../commons/LoadingGraphic.vue';
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'payment',
  components: {
    LoadingGraphic,
    StagesNav,
  },
  data() {
    return {
      card: null,
      cardCvc: this.$root.$data.signup.cardCvc || '',
      cardExpiration: this.$root.$data.signup.cardExpiration || '',
      cardName: this.$root.$data.signup.cardName || '',
      cardNumber: this.$root.$data.signup.cardNumber || '',
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      isComplete: this.$root.$data.signup.billingConfirmed,
      isProcessing: false,
      stripeKey: Laravel.services.stripe.key,
      stripeError: '',
    }
  },
  computed: {
    cardData() {
      return {
        number: this.cardNumber,
        exp_month: this.cardExpiration.substring(0, 2),
        exp_year: this.cardExpiration.substring(5),
        cvc: this.cardCvc,
        address_zip: Laravel.user.zip,
        name: this.cardName,
      }
    },
    subtext() {
      return this.$root.$data.signup.billingConfirmed
        ? ''
        : 'Please enter a credit or debit card to save on file. We will not charge your card until after your first consultation is complete.';
    },
    title() {
      return this.$root.$data.signup.billingConfirmed
        ? 'Payment Method Confirmed!'
        : 'Enter Payment Method';
    }
  },
  methods: {
    onSubmit(e) {
      e.preventDefault();
      this.toggleProcessing();
      this.stripeError = '';

      const errors = this.validateCardInputs();
      if (errors) {
        this.setStripeError(errors);
        return;
      }

      // Setup stripe and create user token
      // Send user token up the wire for storage
      Stripe.card.createToken(this.cardData, (status, response) => {
        if (response.error) {
          this.setStripeError(response.error.message)
        } else {
          this.$root.$data.signup.cardBrand = response.card.brand;
          this.$root.$data.signup.cardLastFour = response.card.last4;
          axios.post(`/api/v1/users/${Laravel.user.id}/cards`, { id: response.id }).then(res => {
            this.$router.push({ name: 'confirmation', path: '/confirmation' });
            this.markComplete();
          }).catch(error => {});
        }
      });
    },
    markComplete() {
      this.isComplete = true;
      this.$root.$data.signup.billingConfirmed = true;
      this.$root.$data.signup.cardCvc = this.cardCvc;
      this.$root.$data.signup.cardExpiration = this.cardExpiration;
      this.$root.$data.signup.cardName = this.cardName;
      this.$root.$data.signup.cardNumber = this.cardNumber;
      this.isProcessing = false;
    },
    resetCardData() {
      this.$root.$data.signup.cardCvc = '';
      this.$root.$data.signup.cardExpiration = '';
      this.$root.$data.signup.cardName = '';
      this.$root.$data.signup.cardNumber = '';
      this.$root.$data.signup.cardBrand = '';
      this.$root.$data.signup.cardLastFour = '';
      this.cardCvc = '';
      this.cardExpiration = '';
      this.cardName = '';
      this.cardNumber = '';
      this.$root.$data.signup.billingConfirmed = false;
      this.isComplete = false;
    },
    setStripeError(msg) {
      this.toggleProcessing();
      this.stripeError = msg;
    },
    toggleProcessing() {
      this.isProcessing = !this.isProcessing;
    },
    validateCardInputs() {
      let result = '';
      if (!this.cardNumber.length) result += 'Card number is blank<br>'
      if (!this.cardName.length) result += 'Card name is blank<br>'
      if (!this.cardExpiration.length) result += 'Card expiration is blank<br>'
      if (!this.cardCvc.length) result += 'Card CVC is blank<br>'
      return result;
    }
  },
  mounted () {
    this.$root.toDashboard();
    Stripe.setPublishableKey(this.stripeKey);
    this.$root.$data.signup.visistedStages.push('payment');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);

    // Card.js - https://github.com/jessepollak/card
    this.card = new Card({
      container: '.credit-card',
      form: '#credit-card-form',
      formSelectors: {
        numberInput: 'input[name="card_number"]',
        expiryInput: 'input[name="card_expiration"]',
        cvcInput: 'input[name="card_cvc"]',
        nameInput: 'input[name="card_name"]'
      },
    });
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
