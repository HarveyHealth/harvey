<template>
    <SlideIn v-if="!State('getstarted.signup.hasCompletedSignup')" class="ph2 ph3-m pv4">

        <div class="mha mw6 tc">
            <Heading1 doesExpand :color="'light'">{{ preface.heading }}</Heading1>
            <Spacer isBottom :size="2" />
            <Paragraph :color="'light'" :weight="'thin'" v-html="preface.subtext"></Paragraph>
            <Spacer isBottom :size="3" />
            <div class="credit-card" v-show="!isConfirmed && !isAlreadyStored"></div>
            <SlideIn v-if="isConfirmed || isAlreadyStored">
                <i class="fa fa-check confirmed-check dib circle f2"></i>
            </SlideIn>
        </div>

        <Spacer isBottom :size="3" />
        <Pagination :step="'payment'" class="mha mw6" />

        <Spacer isBottom :size="1" />
        <Card class="mha mw6">
            <CardContent class="pa4">
                <form id="credit-card-form" @submit.prevent>
                    <div v-show="!isAlreadyStored">
                        <InputText
                            :error="formErrors.cardName"
                            :disabled="isProcessing || isConfirmed"
                            :mode="'bare'"
                            :name="'card_name'"
                            :onBlur="() => { validateFormInput('cardName') }"
                            :onInput="e => { cardName = e.target.value }"
                            :placeholder="'Full Name'"
                            :ref="'card_name'"
                            :value="cardName"
                        />
                        <InputText
                            :error="formErrors.cardNumber"
                            :disabled="isProcessing || isConfirmed"
                            :mode="'bare'"
                            :name="'card_number'"
                            :onBlur="() => { validateFormInput('cardNumber') }"
                            :onInput="e => { cardNumber = e.target.value }"
                            :placeholder="'Card Number'"
                            :value="cardNumber"
                        />
                        <Grid :columns="[{ns:6},{ns:6}]" :gutters="{ns:2}">
                            <div :slot="1">
                                <InputText
                                    :error="formErrors.cardExpiration"
                                    :disabled="isProcessing || isConfirmed"
                                    :mode="'bare'"
                                    :name="'card_expiration'"
                                    :onBlur="() => { validateFormInput('cardExpiration') }"
                                    :onInput="e => { cardExpiration = e.target.value }"
                                    :placeholder="'MM / YYYY'"
                                    :value="cardExpiration"
                                />
                            </div>
                            <div :slot="2">
                                <InputText
                                    :error="formErrors.cardCvc"
                                    :disabled="isProcessing || isConfirmed"
                                    :mode="'bare'"
                                    :name="'card_cvc'"
                                    :onBlur="() => { validateFormInput('cardCvc') }"
                                    :onInput="e => { cardCvc = e.target.value }"
                                    :placeholder="'CVC'"
                                    :value="cardCvc"
                                />
                            </div>
                        </Grid>
                        <InputText
                            :error="discountError"
                            :disabled="isProcessing || isConfirmed"
                            :mode="'bare'"
                            :name="'discount_card'"
                            :onInput="e => { discountCode = e.target.value }"
                            :placeholder="'Discount Code'"
                            :success="discountSuccess"
                            :value="discountCode"
                        />
                    </div>

                    <div v-show="isAlreadyStored" class="tc">
                        <Paragraph>
                            Your card has been confirmed. You can enter new card info here, or continue to the confirmation page.
                        </Paragraph>
                    </div>

                    <div v-if="isConfirmed || isAlreadyStored" class="tc">
                        <Spacer isBottom :size="4" />
                        <InputButton
                            :mode="'secondary'"
                            :text="'Edit Card'"
                            :onClick="resetCardData"
                            :width="'160px'"
                        />
                    </div>


                    <div class="tc" v-if="!isAlreadyStored && !isConfirmed">
                        <Spacer isBottom :size="3" />
                        <span class="db mb2 red" v-show="stripeError" v-html="stripeError"></span>
                        <InputButton
                            :isDisabled="isConfirmed"
                            :isDone="isConfirmed"
                            :isProcessing="isProcessing"
                            :text="'Save & Continue'"
                            :onClick="handleCardSubmission"
                            :width="'160px'"
                        />
                    </div>
                    <Spacer isBottom :size="3" />
                </form>

                <Grid :columns="[{s:6},{s:6}]" :gutters="{s:3}" v-if="!isAlreadyStored && !isConfirmed">
                    <div :slot="1" class="relative image-container">
                        <img src="https://harvey-production.s3.amazonaws.com/assets/images/signup/stripe-lock.png" class="assurance-image fr">
                    </div>
                    <div :slot="2" class="relative image-container">
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/signup/bbb.png" class="assurance-image">
                    </div>
                </Grid>
            </CardContent>
        </Card>
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
            discountSuccess: this.State('getstarted.signup.discountSuccess') || '',
            postError: 'There was an unexpected error. Please try again or contact support at <a href="tel:8006909989">800-690-9989</a>',
            stripeError: '',

            discountError: '',
            formErrors: {
                cardName: '',
                cardNumber: '',
                cardExpiration: '',
                cardCvc: ''
            },
            isProcessing: false
        };
    },
    computed: {
        cardData() {
            return {
                number: this.cardNumber,
                exp_month: this.cardExpiration.substring(0, 2),
                exp_year: this.cardExpiration.substring(5),
                cvc: this.cardCvc,
                address_zip: this.Config.user.info.zip,
                name: this.cardName
            };
        },
        isAlreadyStored() {
            return this.State('getstarted.signup.cardIsStored');
        },
        isConfirmed() {
            return this.State('getstarted.signup.cardNumber').length > 0;
        },
        preface() {
            return this.isConfirmed || this.isAlreadyStored
                ? {
                    heading: 'Payment Confirmed',
                    subtext: ''
                }
                : {
                    heading: 'Enter Payment Method',
                    subtext: `Please enter a preferred method of payment for your 1-hour consultation with Dr. ${this.State('getstarted.signup.practitioner.name')}, ND. Your card will be charged $150 after completion of your appointment. For short-term 0% financing options, <a href="/financing" target="_blank">click here</a>.`
                };
        }
    },
    methods: {
        handleCardSubmission() {
            this.isProcessing = true;
            this.stripeError = '';

            // Form input validation
            const isInvalid = this.validateFormInputs();
            if (isInvalid) {
                // Should still check discount code before returning
                if (this.discountCode) {
                    this.validateDiscount(() => this.isProcessing = false);
                }
                return;
            }

            // If there is a discount code, stripe api is hit after discount has been checked
            if (this.discountCode) {
              this.validateDiscount(() => this.createStripeToken(this.cardData));
            } else {
              this.createStripeToken(this.cardData);
            }
        },
        validateFormInput(name) {
            if (!this[name].length) {
                switch(name) {
                    case 'cardName':
                        this.formErrors[name] = 'Card name is required.';
                        break;
                    case 'cardNumber':
                        this.formErrors[name] = 'Card number is required.';
                        break;
                    case 'cardExpiration':
                        this.formErrors[name] = 'Card expiration is required.';
                        break;
                    case 'cardCvc':
                        this.formErrors[name] = 'Card CVC is required.';
                        break;
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
        validateDiscount(resolve) {
            this.discountError = '';
            this.discountSuccess = '';
            const endpoint = `${this.$root.$data.apiUrl}/discount_codes/${this.discountCode}?applies_to=consultation`;
            axios.get(endpoint).then(response => {
                if (response.data.errors) {
                    this.discountError = 'Discount code is invalid.';
                    this.isProcessing = false;
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
            // Send stripe token to Stripe
            Stripe.card.createToken(cardData, (status, response) => {
                if (response.error) {
                    this.isProcessing = false;
                    this.stripeError = response.error.message;
                } else {
                    const tokenResponse = response;
                    // Update user's card information
                    axios.post(`/api/v1/users/${App.Config.user.info.id}/cards`, { id: response.id }).then(() => {
                        App.setState({
                            'getstarted.signup.cardName': this.cardName,
                            'getstarted.signup.cardNumber': this.cardNumber,
                            'getstarted.signup.cardExpiration': this.cardExpiration,
                            'getstarted.signup.cardCvc': this.cardCvc,
                            'getstarted.signup.data.discount_code': this.discountCode,
                            'getstarted.signup.discountSuccess': this.discountSuccess,
                            'getstarted.signup.cardBrand': tokenResponse.card.brand,
                            'getstarted.signup.cardLastFour': tokenResponse.card.last4
                        });
                        this.isProcessing = false;
                        App.Logic.getstarted.nextStep.call(this, 'payment');
                    }).catch(error => {
                        if (error.response) {
                            this.isProcessing = false;
                            this.stripeError = this.postError;
                        }
                    });
                }
            });
        },
        resetCardData() {
            App.setState({
                'getstarted.signup.cardName': '',
                'getstarted.signup.cardNumber': '',
                'getstarted.signup.cardExpiration': '',
                'getstarted.signup.cardCvc': '',
                'getstarted.signup.cardBrand': '',
                'getstarted.signup.cardLastFour': '',
                'getstarted.signup.cardIsStored': false,
                'getstarted.signup.discountSuccess': '',
                'getstarted.signup.stepsCompleted.payment': false
            });
            this.cardCvc = '';
            this.cardExpiration = '';
            this.cardName = '';
            this.cardNumber = '';
            this.discountSuccess = '';
            Vue.nextTick(() => {
                this.$refs.card_name.focus();
            });
        }
    },
    beforeMount() {
        App.Logic.getstarted.refuseStepSkip.call(this, 'payment');

        if (!this.isConfirmed && this.Config.user.info.has_a_card) {
            App.setState({
                'getstarted.signup.cardIsStored': this.Config.user.info.has_a_card,
                'getstarted.signup.cardNumber': 'just_trigger_text'
            });

            if (!this.State('getstarted.signup.stepsCompleted.payment')) {
                App.Logic.getstarted.nextStep.call(this, 'payment');
            }
        }
    },
    mounted () {
        window.scroll(0, 0);
        Stripe.setPublishableKey(Laravel.services.stripe.key);

        if (this.isAlreadyStored) {
            App.setState('getstarted.signup.stepsCompleted.payment', true);
        }

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
    @import '~sass';

    @include query-up-to(md) {
        .credit-card {
            transform: scale(0.8);
        }
    }

    .image-container {
        line-height: 50px;
    }

    .assurance-image {
        max-width: 80px;
        vertical-align: bottom;
    }

    .confirmed-check {
        border: 9px solid rgba(255, 255, 255, 0.8);
        color: $color-good;
        height: 120px;
        line-height: 100px;
        width: 120px;
    }
</style>
