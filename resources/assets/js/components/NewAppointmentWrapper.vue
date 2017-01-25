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
    import {isEmpty, assign, pick, keys} from 'lodash';
    import moment from 'moment';
    import Form from '../helpers.js';
    import NewAppointment from './NewAppointment.vue';
    import Profile from './Profile.vue';
    import Payment from './Payment.vue';

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
                        // height_inches: 'The height(inches) field is required.',
                        weight: 'The weight field is required.',
                    },
                    'payment': {
                        number: 'The card number field is required.',
                        exp_month: 'The expiration month field is required.',
                        exp_year: 'The expiration year field is required.',
                        cvc: 'The cvc field is required.'
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
                        cvc: ''
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
                this.forms[this.currentStepName].onFail({
                    response: {
                        data: error
                    }
                });
            },
            hasFieldData(field) {
                return this.forms[this.currentStepName] && this.forms[this.currentStepName][field];
            },
            checkError() {
                let error = false;
                let errorData = {};
                let currentStepValidator = this.validationRules[this.currentStepName];

                // check if all required fields are filled
                Object.keys(currentStepValidator).forEach(field => {
                    if (!this.hasFieldData(field)) {
                        // error object in Laravel error format
                        errorData[field] = [currentStepValidator[field]];
                    }
                })

                if (!_.isEmpty(errorData)) {
                    this.formOnError(errorData);

                    error = true;
                } else {
                    // extra validation
                    if (this.currentStepName == 'profile') {
                        // check if birthdate is validate
                        if ( !this.validateDate(this.forms.profile.birthdate) ) {
                             this.formOnError({
                                birthdate: ['Please input a valid birthdate.']
                             });

                            error = true;
                        }
                    }
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
                        // end of the flow
                        this.submitToStripe();
                    }
                }
            },
            validateDate(date) {
                return moment(date, ['MM/DD/YYYY', 'YYYY-MM-DD']).isValid();
            },
            submitToStripe() {
                // disable button first
                this.buttonIsDisabled = true;

                // submit to stripe
                Stripe.card.createToken(this.forms.payment.data(), this.stripeResponseHandler);
            },
            stripeResponseHandler(status, response) {
                if (status == 200) {
                    // if success
                    this.submitForms(response.id);
                } else {
                    this.goBackToErrorComponent({
                        [response.error.param]: [response.error.message]
                    }, 2);
                }
            },
            formatBirthdate(birthdate) {
                // if birthdate does not match 'yyyy-mm-dd'
                if (!birthdate.match(/\d{4}-\d{1,2}-\d{1,2}/g)) {
                    // format it to 'yyyy-mm-dd'
                    return moment(birthdate).format('YYYY-MM-DD');
                }
                return birthdate;
            },
            transformProfileFormData(stripe_id) {
                let profileFormData = this.forms['profile'].data();

                let formattedBirthdate = this.formatBirthdate(profileFormData.birthdate);

                return Object.assign({}, profileFormData, {
                    'birthdate': formattedBirthdate,
                    'stripe_customer_id': stripe_id
                });
            },
            submitForms(stripe_id) {
                // send requests for payment, user profile and new appointment in sequence
                let profileFormData = this.transformProfileFormData(stripe_id);

                this.$http.patch(this.$root.apiUrl + '/users/' + this.$root.userId, profileFormData)
                    .then(() => {
                        this.$http.post(this.$root.apiUrl + '/appointments', this.forms['new-appointment'].data())
                            .then(() => {
                                this.$eventHub.$emit('alert', {
                                    type: 'success',
                                    important: false,
                                    text: 'Your appointment is created successfully.'
                                });
                                this.$router.push('/');
                            })
                            .catch((error) => {
                                this.goBackToErrorComponent(error.response.data, 0);
                            })
                    })
                    .catch((error) => {
                        this.goBackToErrorComponent(error.response.data, 1);
                    });
            },
            goBackToErrorComponent(error, step) {
                // first enable button
                this.buttonIsDisabled = false;

                // go to component
                if (this.currentStep != step) {
                    this.currentStep = step;
                }

                // record error
                this.formOnError(error);
            },
            assignUserData() {
                if (!_.isEmpty(this.user)) {
                    this.forms.profile = _.assign(this.forms.profile, _.pick(this.user, _.keys(this.forms.profile)));
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
            this.assignUserData();
        },
        watch: {
            user() {
                this.assignUserData();
            }
        }
    }
</script>