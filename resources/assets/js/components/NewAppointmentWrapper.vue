<template>
    <div class="container">
        <div class="hero has-text-centered">
            <div class="hero-body">
                <h1 class="title" v-text="titles[currentStep]"></h1>
            </div>
        </div>
        <transition
            name="component-fade"
            mode="out-in"
            enter-active-class="animated animated-fast fadeIn"
            leave-active-class="animated animated-fast fadeOut"
        >
            <compoonent
                :is="steps[currentStep]"
                :include-cta="false"
                :form.sync="forms[currentStepName]"
            ></compoonent>
        </transition>
        <div class="is-clearfix hero-buttons">
            <button
                class="button is-medium is-primary"
                v-text="buttonTexts[currentStep]"
                @click="goToNextStep"
                :disabled="buttonIsDisabled"
            ></button>
        </div>
    </div>
</template>

<script>
    import Form from '../helpers.js';
    import NewAppointment from './NewAppointment.vue';
    import Profile from './Profile.vue';
    import Payment from './Payment.vue';
    import {isEmpty} from 'lodash';

    export default {
        name: 'new-appointment-wrapper',
        props: ['user'],
        data() {
            return {
                steps: ['new-appointment', 'profile', 'payment'],
                buttonTexts: ['Schedule', 'Save', 'Save card'],
                titles: [
                    'Choose appointment time',
                    'Your doctor will need additional info',
                    'Payment details'
                ],
                currentStep: 0,
                buttonIsDisabled: false,
                validationRules: {
                    'new-appointment': {
                        details: 'Please explain how we can help you in details.'
                    },
                    'profile': {
                        first_name: 'Please tell us your first name.',
                        last_name: 'Please tell us your last name.',
                        email: 'The email field is required.',
                        phone: 'The phone field is required.',
                        gender: 'Please tell us your gender.',
                        birthdate: 'Please tell us your birthdate.',
                        height_feet: 'The height(feet) field is required.',
                        height_inches: 'The height(inches) field is required.',
                        weight: 'The weight field is required.',
                    },
                    'payment': {
                        number: 'The card number field is required.',
                        exp_month: 'The expiration month field is required.',
                        exp_year: 'The expiration year field is required.',
                        cvc: 'The cvc field is required.',
                        // address_zip: 'The billing zip code field is required.'
                    }
                },
                forms: {
                    'new-appointment': new Form({
                        selectedDate: '',
                        selectedTime: '',
                        details: ''
                    }),
                    'profile': new Form({
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone: '',
                        gender: '',
                        birthdate: '',
                        height_feet: '',
                        height_inches: '',
                        weight: ''
                    }),
                    'payment': new Form({
                        number: '',
                        exp_month: '',
                        exp_year: '',
                        cvc: '',
                        // address_zip: ''
                    })
                }
            }
        },
        components: {
            NewAppointment,
            Profile,
            Payment
        },
        methods: {
            formOnError(error) {
                this.forms[this.currentStepName].onFail(error);
            },
            hasFieldData(field) {
                return Boolean(this.forms[this.currentStepName] && this.forms[this.currentStepName][field]);
            },
            checkError() {
                let error = true;
                let errorData = {};

                Object.keys(this.validationRules[this.currentStepName]).forEach(field => {
                    if (!this.hasFieldData(field)) {
                        errorData[field] = [this.validationRules[this.currentStepName][field]];
                    }
                })

                if (!_.isEmpty(errorData)) {
                    this.formOnError({
                        response: {
                            data: errorData
                        }
                    });
                } else {
                    error = false;
                }

                return error;
            },
            goToNextStep() {
                // check error on current step
                let error = this.checkError();

                if (!error) {                    
                    // if no error, go to next step
                    if (this.currentStep < this.steps.length - 1) {
                        this.currentStep++;
                    } else {
                        // end of the flow, disable button first
                        this.buttonIsDisabled = true;
                        
                        // submit to stripe
                        Stripe.card.createToken(this.forms.payment.data(), this.stripeResponseHandler);
                    }
                }
            },
            paymentFormOnError(response) {
                this.formOnError({
                    response: {
                        data: {
                            [response.param]: [response.message]
                        }
                    }
                });
            },
            stripeResponseHandler(status, response) {
                console.log(status, response)
                if (status == 200) {
                    // if success, send request for new appointment, user profile and payment
                    // payment: response.id
                    axios.all([
                        axios.post('api/appointments', this.forms['new-appointment'].data()),
                        axios.put('api/users', this.forms['profile'].data())
                    ])
                    .then( axios.spread(() => {
                        this.$router.push('/');
                    }) )
                    .catch( (error) => {
                        this.buttonIsDisabled = false;
                        console.log(error.response)
                        this.goBackToErrorComponent(error.response);
                    } );
                } else {
                    // if error, display error and enable button
                    this.buttonIsDisabled = false;
                    this.paymentFormOnError(response.error);
                }
            },
            goBackToErrorComponent(error) {
                if (error.config.url == 'api/users') {
                    this.currentStep = 1;
                } else {
                    this.currentStep = 0;
                }
                this.formOnError(error.data);
            },
            mergeUserProfile() {
                if (!_.isEmpty(this.user)) {
                    this.forms.profile = Object.assign(this.forms.profile, this.user);
                }
            }
        },
        computed: {
            isFirstTimeUser() {
                return !this.user.gender;
            },
            currentStepName() {
                return this.steps[this.currentStep];
            }
        },
        mounted() {
            this.mergeUserProfile();
        },
        watch: {
            user() {
                this.mergeUserProfile();
            }
        }
    }
</script>

<style lang="sass">

</style>