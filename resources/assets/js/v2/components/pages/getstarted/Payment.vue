<template>
    <SlideIn v-if="!State('getstarted.signup.hasCompletedSignup')" class="ph2 ph3-m pv4">

        <div class="mha mw6 tc">
            <Heading1 doesExpand :color="'light'">Enter Payment Method</Heading1>
            <Spacer isBottom :size="2" />
            <Paragraph :color="'light'" :weight="'thin'">
                Please enter a preferred method of payment for your 1-hour consultation with Dr.  , ND. Your card will be charged $150 after completion of your appointment. For short-term 0% financing options, click here.
            </Paragraph>
            <Spacer isBottom :size="3" />
            <div class="credit-card" v-show="!$root.$data.signup.billingConfirmed"></div>
        </div>

        <Spacer isBottom :size="3" />
        <Pagination :step="'payment'" class="mha mw6" />

        <Spacer isBottom :size="1" />
        <Card class="mha mw6">
            <CardContent class="pa4">
                <form id="credit-card-form" @submit.prevent>
                    <InputText
                        :error="formErrors.cardName"
                        :mode="'bare'"
                        :name="'card_name'"
                        :onBlur="() => { validateFormInput('cardName') }"
                        :onInput="e => { cardName = e.target.value }"
                        :placeholder="'Full Name'"
                        :value="cardName"
                    />
                    <InputText
                        :error="formErrors.cardNumber"
                        :mode="'bare'"
                        :name="'card_number'"
                        :onBlur="() => { validateFormInput('cardNumber') }"
                        :onInput="e => { cardNumber = e.target.value }"
                        :placeholder="'Card Number'"
                        :value="cardNumber"
                    />
                    <Grid :flexAt="'m'" :columns="[{m:'1of2'},{m:'1of2'}]" :gutters="{m:2}">
                        <InputText
                            :error="formErrors.cardExpiration"
                            :slot="1"
                            :mode="'bare'"
                            :name="'card_expiration'"
                            :onBlur="() => { validateFormInput('cardExpiration') }"
                            :onInput="e => { cardExpiration = e.target.value }"
                            :placeholder="'MM / YYYY'"
                            :value="cardExpiration"
                        />
                        <InputText
                            :error="formErrors.cardCvc"
                            :slot="2"
                            :mode="'bare'"
                            :name="'card_cvc'"
                            :onBlur="() => { validateFormInput('cardCvc') }"
                            :onInput="e => { cardCvc = e.target.value }"
                            :placeholder="'CVC'"
                            :value="cardCvc"
                        />
                    </Grid>
                    <InputText
                        :mode="'bare'"
                        :name="'discount_card'"
                        :onInput="e => { discountCode = e.target.value }"
                        :placeholder="'Discount Code'"
                        :value="discountCode"
                    />

                    <div class="tc">
                        <InputButton
                            :isProcessing="isProcessing"
                            :text="'Save & Continue'"
                            :onClick="handleCardSubmission"
                            :width="'160px'"
                        />
                    </div>
                    <Spacer isBottom :size="3" />
                </form>

                <Grid :columns="[{s:'1of2'},{s:'1of2'}]" :gutters="{s:3}">
                    <div :slot="1" class="relative image-container">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/signup/stripe-lock.png" class="assurance-image fr">
                    </div>
                    <div :slot="2" class="relative image-container">
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/signup/bbb.png" class="assurance-image">
                    </div>
                </Grid>
            </CardContent>
        </Card>

        <!-- <div v-if="!pageLogic.showForm" class="signup-main-icon">
          <svg>
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#checkmark"></use>
          </svg>
        </div>
        <p v-if="!pageLogic.showForm">Your card has been confirmed. You can enter new card info here, or continue to the confirmation page.</p>
        <p class="copy-error" v-show="stripeError.length" v-html="stripeError"></p>
        <button class="button button--cancel" v-show="pageLogic.editButton" @click="resetCardData">New Card</button>
        <button class="button button--blue" :disabled="pageLogic.submitDisabled" @click="onSubmit($event)">
        </button>
        <div class="trust-logos input-half--sm margin-0a is-padding-top">
          <img src="https://harvey-production.s3.amazonaws.com/assets/images/signup/stripe-lock.png" class="stripe">
          <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/signup/bbb.png" class="bbb">
        </div>
      </div>  -->

  </SlideIn>
</template>

<script>
import { InputButton, InputText } from 'inputs';
import { Card, CardContent, Grid, SlideIn, Spacer } from 'layout';
import { Heading1, Paragraph } from 'typography';

import Pagination from './Pagination.vue';

export default {
    name: 'payment',
    components: {
        Card,
        CardContent,
        Grid,
        Heading1,
        InputButton,
        InputText,
        Pagination,
        Paragraph,
        SlideIn,
        Spacer
    },
    data() {
        return {
            card: null,
            cardCvc: this.State('getstarted.signup.cardCvc') || '',
            cardExpiration: this.State('getstarted.signup.cardExpiration') || '',
            cardName: this.State('getstarted.signup.cardName') || '',
            cardNumber: this.State('getstarted.signup.cardNumber') || '',
            discountCode: this.State('getstarted.signup.data.discount_code') || '',
            discountError: '',
            discountSuccess: '',
            hasCardStored: this.Config.user.info.has_a_card,
            isComplete: this.$root.$data.signup.billingConfirmed,
            isValidDiscount: false,
            postError: 'There was an unexpected error. Please try again or contact support at <a href="tel:8006909989">800-690-9989</a>',
            stripeKey: Laravel.services.stripe.key,
            stripeError: '',

            formErrors: {
                cardName: '',
                cardNumber: '',
                cardExpiration: '',
                cardCvc: ''
            },
            isProcessing: false,
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
      const dr = this.$root.$data.signup.practitionerName;
      return this.$root.$data.signup.billingConfirmed
        ? ''
        : `Please enter a preferred method of payment for your 1-hour consultation with Dr. ${dr}, ND. Your card will be charged $150 after completion of your appointment. For short-term 0% financing options, <a href="/financing" target="_blank">click here</a>.`;
    },
    title() {
      return this.$root.$data.signup.billingConfirmed
        ? 'Confirm Payment'
        : 'Enter Payment Method';
    }
  },
    methods: {
        handleCardSubmission() {
            // is processing
            this.isProcessing = true;
            // reset feedback

            // input validation
            const isInvalid = this.validateFormInputs();
            if (isInvalid) {
                this.isProcessing = false;
                return;
            }
            // discount check -> submitCardData
            // or submitCardData
            // toggle state
        },
        validateFormInput(name) {
            if (!this[name].length) {
                switch(name) {
                    case 'cardName': this.formErrors[name] = 'Card name is required.';
                    case 'cardNumber': this.formErrors[name] = 'Card number is required.';
                    case 'cardExpiration': this.formErrors[name] = 'Card expiration is required.';
                    case 'cardCvc': this.formErrors[name] = 'Card CVC is required.';
                }
            } else {
                this.formErrors[name] = '';
            }
            return name;
        },
        validateFormInputs() {
            Object.keys(this.formErrors).map(key => this.validateFormInput(key));
            return Object.keys(this.formErrors).filter(key => this.formErrors[key].length).length > 0;
        },

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

    // Card.js - https://github.com/jessepollak/card
    this.card = new window.Card({
      container: '.credit-card',
      form: '#credit-card-form',
      formSelectors: {
        numberInput: 'input[name="card_number"]',
        expiryInput: 'input[name="card_expiration"]',
        cvcInput: 'input[name="card_cvc"]',
        nameInput: 'input[name="card_name"]'
      }
    });
    analytics.page('Payment');
  }
};
</script>

<style lang="scss" scoped>
    .image-container {
        line-height: 50px;
    }
    .assurance-image {
        max-width: 80px;
        vertical-align: bottom;
    }
</style>
