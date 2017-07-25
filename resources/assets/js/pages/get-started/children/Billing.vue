<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'billing'" />
      <h2 v-text="title"></h2>
      <p v-html="subtext"></p>
      <div :class="{ 'credit-card': true, 'isInactive': cardInactive }">
        <div class="credit-card__chip"></div>
        <div class="credit-card__brand"><svg><use :xlink:href="'#cc'+cardBrand" /></svg></div>
        <div class="credit-card__number">{{ cardNumber | cardNumberFilter }}</div>
        <div class="credit-card__name">{{ cardName | cardNameFilter }}</div>
        <div class="credit-card__date">{{ cardExpiration | cardExpirationFilter }}</div>
      </div>
    </div>
    <div class="signup-container signup-phone-container text-centered">
      <router-link class="signup-back-button" :to="{ name: 'schedule', path: '/schedule' }"><i class="fa fa-long-arrow-left"></i><span>Schedule</span></router-link>

      <div class="phone-input-container">
        <div class="input-container cf">
          <!-- CARD NUMBER -->
          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray"
                   :disabled="isComplete"
                   name="card_number" type="text" placeholder="Card Number" maxlength="16" v-model="cardNumber"
                   v-validate="{ required: true, digits: 16 }" data-vv-as="Card Number" data-vv-validate-on="blur" />
            <span v-show="errors.has('card_number')" class="error-text">{{ errors.first('card_number') }}</span>
          </div>
          <!-- CARD NAME -->
          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray"
                   :disabled="isComplete"
                   name="card_name" type="text" placeholder="Name on Card" v-model="cardName"
                   v-validate="'required|alpha_spaces'" data-vv-as="Name on Card" data-vv-validate-on="blur" />
            <span v-show="errors.has('card_name')" class="error-text">{{ errors.first('card_name') }}</span>
          </div>
          <div>
            <!-- CARD EXPIRATION DATE -->
            <div class="input-wrap input-half--sm">
              <input class="form-input form-input_text font-base font-darkest-gray"
                     :disabled="isComplete"
                     name="card_expiration" type="text" placeholder="MMYY" maxlength="4" v-model="cardExpiration"
                     v-validate="{ required: true, digits: 4 }" data-vv-as="Card Expiration" data-vv-validate-on="blur" />
              <span v-show="errors.has('card_expiration')" class="error-text">{{ errors.first('card_expiration') }}</span>
            </div>
            <!-- CARD CVC NUMBER -->
            <div class="input-wrap input-half--sm last">
              <input class="form-input form-input_text font-base font-darkest-gray"
                     :disabled="isComplete"
                     name="card_cvc" type="text" placeholder="CVC" maxlength="3" v-model="cardCvc"
                     v-validate="{ required: true, digits: 3 }" data-vv-as="Card CVC" data-vv-validate-on="blur" />
              <span v-show="errors.has('card_cvc')" class="error-text">{{ errors.first('card_cvc') }}</span>
            </div>
          </div>
        </div>

        <p>We will not charge you. You can confirm your appointment date and time on the next page.</p>
        <p class="error-text" v-show="stripeError.length">{{ stripeError }}</p>
        <button class="button button--blue" style="width: 180px" :disabled="isProcessing || isComplete" @click="onSubmit">
          <span v-if="!isProcessing && !isComplete">Save &amp; Continue</span>
          <LoadingBubbles v-else-if="isProcessing" :style="{ width: '12px', fill: 'white' }" />
          <i v-else-if="isComplete" class="fa fa-check"></i>
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
import StagesNav from '../util/StagesNav.vue';

export default {
  name: 'billing',
  components: {
    LoadingBubbles,
    StagesNav,
  },
  data() {
    return {
      cardBrand: '',
      cardCvc: '',
      cardExpiration: '',
      cardName: '',
      cardNumber: '',
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      isComplete: false,
      isProcessing: false,
      stripeKey: 'pk_test_P1CltTCM3UIMzka8O1p7J2MT',
      stripeError: '',
      subtext: 'Please enter a credit or debit card to save on file. We will not charge your card until after your first consultation is complete.',
      title: 'Enter Payment Method',
    }
  },
  watch: {
    cardNumber(value) {
      this.cardBrand = Stripe.card.cardType(value);
    }
  },
  filters: {
    cardExpirationFilter(value) {
      let exp = value.split('');
      exp.splice(2, 0, '/');
      return value.length ? exp.join('') : 'MM/YY';
    },
    cardNameFilter(value) {
      return value.toUpperCase().substring(0,18) || 'FULL NAME';
    },
    cardNumberFilter(value) {
      return value
        .split('')
        .map((num, i) => (i + 1) % 4 === 0 ? `${num} ` : num)
        .join('') || '•••• •••• •••• ••••';
    }
  },
  computed: {
    cardData() {
      return {
        number: this.cardNumber,
        exp_month: this.cardExpiration.substring(0, 2),
        exp_year: this.cardExpiration.substring(2, 4),
        cvc: this.cardCvc,
        address_zip: Laravel.user.zip,
        name: this.cardName,
      }
    },
    cardInactive() {
      return !this.cardCvc && !this.cardExpiration && !this.cardName && !this.cardNumber;
    }
  },
  methods: {
    onSubmit() {
      this.toggleProcessing();
      this.stripeError = '';

      this.$validator.validateAll().then(response => {
        if (!response) {
          this.toggleProcessing();
          return;
        } else {
          // Setup stripe and create user token
          Stripe.card.createToken(this.cardData, (status, response) => {
            if (response.error) {
              this.toggleProcessing();
              this.stripeError = response.error.message;
            } else {
              this.markComplete();
              const stripeData = {
                stripe_brand: response.card.brand,
                stripe_customer_id: response.id,
                stripe_expiry_month: response.card.exp_month,
                stripe_expiry_year: response.card.exp_year,
                stripe_last_four: response.card.last4
              }
            }
          });
        }
      }).catch(error => {});
    },
    markComplete() {
      this.isComplete = true;
      this.isProcessing = false;
    },
    toggleProcessing() {
      this.isProcessing = !this.isProcessing;
    }
  },
  mounted () {
    this.$root.toDashboard();
    Stripe.setPublishableKey(this.stripeKey);
    this.$root.$data.signup.visistedStages.push('billing');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
