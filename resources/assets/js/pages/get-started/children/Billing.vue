<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'billing'" />
      <h2 v-text="title"></h2>
      <p v-html="subtext"></p>
      <div :class="{ 'credit-card': true, 'isInactive': cardInactive }">
        <div class="credit-card__chip"></div>
        <div class="credit-card__brand"></div>
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
                   name="card_number" type="text" placeholder="Card Number" maxlength="16" v-model="cardNumber"
                   v-validate="{ required: true, digits: 16 }" data-vv-as="Card Number" data-vv-validate-on="blur" />
            <span v-show="errors.has('card_number')" class="error-text">{{ errors.first('card_number') }}</span>
          </div>
          <!-- CARD NAME -->
          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray"
                   name="card_name" type="text" placeholder="Name on Card" v-model="cardName"
                   v-validate="'required|alpha_spaces'" data-vv-as="Name on Card" data-vv-validate-on="blur" />
            <span v-show="errors.has('card_name')" class="error-text">{{ errors.first('card_name') }}</span>
          </div>
          <div>
            <!-- CARD EXPIRATION DATE -->
            <div class="input-wrap input-half--sm">
              <input class="form-input form-input_text font-base font-darkest-gray"
                     name="card_expiration" type="text" placeholder="MMYY" maxlength="4" v-model="cardExpiration"
                     v-validate="{ required: true, digits: 4 }" data-vv-as="Card Expiration" data-vv-validate-on="blur" />
              <span v-show="errors.has('card_expiration')" class="error-text">{{ errors.first('card_expiration') }}</span>
            </div>
            <!-- CARD CVC NUMBER -->
            <div class="input-wrap input-half--sm last">
              <input class="form-input form-input_text font-base font-darkest-gray"
                     name="card_cvc" type="text" placeholder="CVC" maxlength="3" v-model="cardCvc"
                     v-validate="{ required: true, digits: 3 }" data-vv-as="Card CVC" data-vv-validate-on="blur" />
              <span v-show="errors.has('card_cvc')" class="error-text">{{ errors.first('card_cvc') }}</span>
            </div>
          </div>
        </div>

        <p>We will not charge you. You can confirm your appointment date and time on the next page.</p>

        <button class="button button--blue" style="width: 180px" :disabled="isProcessingInfo">
          <span v-if="!isProcessingInfo">Save &amp; Continue</span>
          <LoadingBubbles v-else-if="isProcessingInfo" :style="{ width: '12px', fill: 'white' }" />
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
      cardCvc: '',
      cardExpiration: '',
      cardName: '',
      cardNumber: '',
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      isProcessingInfo: false,
      subtext: 'Please enter a credit or debit card to save on file. We will not charge your card until after your first consultation is complete.',
      title: 'Enter Payment Method',
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
    cardInactive() {
      return !this.cardCvc && !this.cardExpiration && !this.cardName && !this.cardNumber;
    }
  },
  methods: {
  },
  mounted () {
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('billing');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
