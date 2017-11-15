<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="vertical-center">
      <div class="signup-stage-instructions color-white">
        <StagesNav :current="'payment'" />
        <h2 class="heading-1 color-white" v-text="title"></h2>
        <p v-html="subtext"></p>

        <div class="credit-card" v-show="!$root.$data.signup.billingConfirmed"></div>
      </div>
      <div class="signup-container small router">
        <router-link class="signup-back-button" :to="{ name: 'schedule', path: '/schedule' }">
          <i class="fa fa-long-arrow-left"></i>
          <span class="font-sm">Schedule</span>
        </router-link>
        <form id="credit-card-form" class="input-container cf" v-show="pageLogic.showForm">
          <div class="input-wrap">
            <input class="form-input form-input_text"
                  :disabled="isComplete" name="card_number" type="text" placeholder="Card Number" v-model="cardNumber" />
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text"
                  :disabled="isComplete" name="card_name" type="text" placeholder="Name on Card" v-model="cardName" />
          </div>
          <div>
            <div class="input-wrap input-half--sm">
              <input class="form-input form-input_text"
                    :disabled="isComplete" name="card_expiration" type="text" placeholder="MM/YY" v-model="cardExpiration" />
            </div>
            <div class="input-wrap input-half--sm last">
              <input class="form-input form-input_text"
                    :disabled="isComplete" name="card_cvc" type="text" placeholder="CVC" v-model="cardCvc" />
            </div>
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text"
                  :disabled="isComplete" name="discount_card" type="text" placeholder="Discount Code" v-model="discountCode" />
            <div class="copy-error" v-show="discountError">{{ discountError }}</div>
            <div class="copy-good" v-show="isValidDiscount">{{ discountSuccess }}</div>
          </div>
        </form>
        <div v-if="!pageLogic.showForm" class="signup-main-icon">
          <svg>
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkmark"></use>
          </svg>
        </div>
        <p v-if="!pageLogic.showForm">Your card has been confirmed. You can enter new card info here, or continue to the confirmation page.</p>
        <p class="copy-error" v-show="stripeError.length" v-html="stripeError"></p>
        <button class="button button--cancel" v-show="pageLogic.editButton" @click="resetCardData">New Card</button>
        <button class="button button--blue" :disabled="pageLogic.submitDisabled" @click="onSubmit($event)">
          <ClipLoader v-if="pageLogic.formProcessing" :color="'#ffffff'" :size="'12px'" />
          <span v-else-if="pageLogic.needSave">Save &amp; Continue</span>
          <span v-else-if="pageLogic.submitContinue"><i class="fa fa-check"></i> Continue</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'payment',
  components: {
    ClipLoader,
    StagesNav
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
        'pad-md': true,
        'flex-wrapper': true,
        'height-100': true,
        'justify-center': true
      },
      discountCode: this.$root.$data.signup.data.discount_code || '',
      discountError: '',
      discountSuccess: '',
      hasCardStored: Laravel.user.has_a_card,
      isComplete: this.$root.$data.signup.billingConfirmed,
      isProcessing: false,
      isValidDiscount: false,
      postError: 'There was an unexpected error. Please try again or contact support at <a href="tel:8006909989">800-690-9989</a>',
      stripeKey: Laravel.services.stripe.key,
      stripeError: ''
    };
  },
  computed: {
    pageLogic() {
      return {
        submitContinue: this.isComplete,
        submitDisabled: this.isProcessing,
        editButton: this.isComplete,
        showForm: (this.hasCardStored && !this.isComplete) || !this.hasCardStored,
        formProcessing: this.isProcessing && !this.isComplete,
        needSave: !this.isComplete
      };
    },
    cardData() {
      return {
        number: this.cardNumber,
        exp_month: this.cardExpiration.substring(0, 2),
        exp_year: this.cardExpiration.substring(5),
        cvc: this.cardCvc,
        address_zip: Laravel.user.zip,
        name: this.cardName
      };
    },
    subtext() {
      return this.$root.$data.signup.billingConfirmed
        ? ''
        : 'Please enter a credit or debit card to save on file. We will not charge your card until after your first consultation is complete.';
    },
    title() {
      return this.$root.$data.signup.billingConfirmed
        ? 'Confirm Payment'
        : 'Enter Payment Method';
    }
  },
  methods: {
    onSubmit(e) {
      e.preventDefault();
      this.toggleProcessing();
      this.stripeError = '';
      this.discountError = '';
      this.discountSuccess = '';
      this.isValidDiscount = false;

      if (this.pageLogic.submitContinue) {
        this.$router.push({ name: 'confirmation', path: '/confirmation' });
        return;
      }

      const errors = this.validateCardInputs();
      if (errors) {
        this.setStripeError(errors);
        if (this.discountCode) this.validateDiscount();
        return;
      }

      if (this.discountCode) {
        this.validateDiscount(() => this.createStripeToken(this.cardData));
      } else {
        this.createStripeToken(this.cardData);
      }

    },
    validateDiscount(resolve) {
      const endpoint = `${this.$root.$data.apiUrl}/discount_codes/${this.discountCode}?applies_to=consultation`;
      axios.get(endpoint).then(response => {
        if (response.data.errors) {
          this.discountError = 'Invalid discount code';
          if (!this.stripeError) {
            this.toggleProcessing();
          }
          return;
        }
        const attributes = response.data.data.attributes;
        this.isValidDiscount = true;
        switch(attributes.discount_type) {
          case 'dollars':
            this.discountSuccess = `$${attributes.amount} consultation discount applied!`;
            break;
          case 'percent':
            this.discountSuccess = `${attributes.amount}% consultation discount applied!`;
            break;
        }
        if (resolve) resolve();
      }).catch(() => {});
    },
    createStripeToken(cardData) {
      Stripe.card.createToken(cardData, (status, response) => {
        if (response.error) {
          this.setStripeError(response.error.message);
        } else {
          this.$root.$data.signup.cardBrand = response.card.brand;
          this.$root.$data.signup.cardLastFour = response.card.last4;
          this.$root.$data.signup.data.discount_code = this.discountCode;
          axios.post(`/api/v1/users/${Laravel.user.id}/cards`, { id: response.id }).then(() => {
            this.$router.push({ name: 'confirmation', path: '/confirmation' });
            this.markComplete();
          }).catch(error => {
            if (error.response) {
              this.setStripeError(this.postError);
            }
          });
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
      Laravel.user.has_a_card = false;
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
      if (!this.cardNumber.length) result += 'Your card number is blank.<br>';
      if (!this.cardName.length) result += 'Your card name is blank.<br>';
      if (!this.cardExpiration.length) result += 'Your card expiration is blank.<br>';
      if (!this.cardCvc.length) result += 'Your card CVC is blank.<br>';
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
      }
    });
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
};
</script>
