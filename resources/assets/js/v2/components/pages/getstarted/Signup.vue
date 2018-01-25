<template>
    <div>
        <div v-if="facebookRedirectAlert">
            <SlideIn class="bg-error pa3 white">{{ facebookRedirectAlert }}</SlideIn>
        </div>

        <SlideIn v-if="!hasZipValidation || !isServiceable" class="margin-0a mw6 ph2 ph3-l top-space">
            <ZipValidation />
        </SlideIn>

        <div v-if="hasZipValidation && isServiceable" class="top-space ph2 ph3-l max-width-xxl min-width-100 margin-0a">
            <SlideIn :delay="400" >
                <Grid :flexAt="'xl'" :columns="[{ xl:6, xxl:7 }, { xl:6, xxl:5 }]">
                    <aside :slot="1" class="dn db-xl relative">
                        <div class="pr2 pr3-l quote-container">
                            <div class="signup-aside-icon-row">
                                <span><svg><use xlink:href="#apple" /></svg></span>
                                <span><svg><use xlink:href="#stethoscope" /></svg></span>
                                <span><svg><use xlink:href="#labs" /></svg></span>
                                <span><svg><use xlink:href="#doctor" /></svg></span>
                                <span class="hide-icon"><svg><use xlink:href="#carrot" /></svg></span>
                                <span class="hide-icon"><svg class="use-stroke"><use xlink:href="#wellness" /></svg></span>
                            </div>
                            <div class="signup-aside-text">
                                <div class="logo-wrapper tc">
                                    <a href="/">
                                        <SvgIcon class="MainNav_Logo" :id="'harvey-logo'" />
                                    </a>
                                </div>
                                <p class="font-xl color-white is-padding font-centered">{{ quotes[0].quote }}</p>
                            </div>
                            <div class="signup-aside-icon-row">
                                <span><svg><use xlink:href="#heart" /></svg></span>
                                <span><svg><use xlink:href="#bottle" /></svg></span>
                                <span><svg><use xlink:href="#baby" /></svg></span>
                                <span><svg><use xlink:href="#scale" /></svg></span>
                                <span class="hide-icon"><svg><use xlink:href="#yoga" /></svg></span>
                                <span class="hide-icon"><svg><use xlink:href="#medicine" /></svg></span>
                            </div>
                        </div>
                    </aside>
                    <Card class="mha mv3 max-width-md" :slot="2">
                        <CardContent class="pa4-m pa3-l pa4-xl">
                            <Spacer isTop :size="2" />
                            <Heading1 doesExpand class="tc" v-html="title"></Heading1>
                            <Spacer isTop :size="2" />

                            <form @submit.prevent="onSubmit">
                                <div class="input-wrap">
                                    <input class="form-input form-input_text"
                                        @change="Util.data.toStorage('first_name', State('getstarted.userPost.first_name'))"
                                        name="first_name" type="text" placeholder="First Name"
                                        v-model="State('getstarted.userPost').first_name"
                                        v-validate="'required|alpha_spaces'" data-vv-as="First name" />
                                    <p v-show="errors.has('first_name')" class="copy-error">{{ firstNameError }}</p>
                                </div>

                                <div class="input-wrap">
                                    <input class="form-input form-input_text"
                                        @change="Util.data.toStorage('last_name', State('getstarted.userPost.last_name'))"
                                        name="last_name" type="text" placeholder="Last Name"
                                        v-model="State('getstarted.userPost').last_name"
                                        v-validate="'required|alpha_spaces'" data-vv-as="Last name" />
                                    <p v-show="errors.has('last_name')" class="copy-error">{{ lastNameError }}</p>
                                </div>

                                <div class="input-wrap">
                                    <input class="form-input form-input_text"
                                        @change="Util.data.toStorage('email', State('getstarted.userPost.email'))"
                                        name="email" type="email" placeholder="Personal Email"
                                        v-model="State('getstarted.userPost').email"
                                        v-validate="'required|email'" data-vv-validate-on="blur" />
                                    <p v-show="errors.has('email')" class="copy-error">{{ emailError }}</p>
                                </div>

                                <div class="input-wrap">
                                    <input class="form-input form-input_text"
                                        @change="Util.data.toStorage('password', State('getstarted.userPost.password'))"
                                        name="password" type="password" placeholder="Create Password"
                                        v-model="State('getstarted.userPost').password"
                                        v-validate="{ required: true, min: 6 }" data-vv-validate-on="blur" />
                                    <p v-show="errors.has('password')" class="copy-error">{{ passwordError }}</p>
                                </div>

                                <div class="input-wrap last">
                                    <label class="form-label form-label_checkbox font-sm" for="checkbox">
                                        <input class="form-input form-input_checkbox"
                                            name="terms" type="checkbox" id="checkbox"
                                            v-model="State('getstarted.userPost').terms"
                                            v-validate="'required'"
                                            checked="checked" /> I agree to <span class="is-hidden-mobile">Harvey's</span> <a href="http://help.goharvey.com/legal/terms">terms</a> and <a href="http://help.goharvey.com/legal/privacy">policies</a>.
                                    </label>
                                    <p v-show="errors.has('terms')" class="copy-error">{{ termsError }}</p>
                                </div>
                                <div class="font-centered">
                                    <InputButton
                                        :isDisabled="isProcessing"
                                        :isDone="isComplete"
                                        :isProcessing="isProcessing"
                                        :onClick="() => false"
                                        :text="'Sign Up'"
                                        :width="'160px'"
                                    />
                                    <div class="Divider-text is-white" data-text="OR"></div>
                                    <FacebookSignin :type="'signup'" :on-click="facebookSignup" />
                                    <p class="is-padding font-xs"><em>We never share personal health information.</em></p>
                                    <p class="font-md">
                                        <a href="#" @click.prevent="Logic.getstarted.resetZip">
                                            <i class="fa fa-globe margin-right-xs"></i>Re-Enter Zip Code
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                </Grid>
            </SlideIn>
        </div>
    </div>
</template>

<script>
import { SvgIcon } from 'icons';
import { InputButton, FacebookSignin } from 'inputs';
import { Card, CardContent, Grid, SlideIn, Spacer } from 'layout';
import { Heading1 } from 'typography';

import ZipValidation from './ZipValidation.vue';

export default {
    name: 'sign-up',
    components: {
        Heading1,
        Card,
        CardContent,
        Grid,
        InputButton,
        FacebookSignin,
        SlideIn,
        Spacer,
        SvgIcon,
        ZipValidation
    },

    data() {
        return {
            facebookRedirectAlert: window.Blade.facebook_redirect_alert || null,
            isComplete: false,
            isProcessing: false,
            quotes: [
                { quote: 'I can say without a shadow of a doubt, my Naturopathic Doctor gave me my life back.',
                source: 'Elizabeth Yorn (Missouri, battling Lupus)' }
            ],
            responseErrors: [],
            subtitle: '',
            zipInRange: false
        };
    },

    // These are necessary because VeeValidate's custom messages are just not working
    // http://vee-validate.logaretm.com/rules.html#field-sepecific-messages
    computed: {
        hasZipValidation() {
            return this.State('getstarted.zipValidation') !== null;
        },

        isServiceable() {
            return this.State('getstarted.zipValidation.is_serviceable');
        },

        firstNameError() {
            if (this.errors.has('first_name')) {
                return this.errors.firstByRule('first_name', 'required')
                    ? 'First name is required.'
                    : 'First name only takes alphabetic characters.';
            }
        },
        lastNameError() {
            if (this.errors.has('last_name')) {
                return this.errors.firstByRule('last_name', 'required')
                ? 'Last name is required.'
                : 'Last name only takes alphabetic characters.';
            }
        },
        emailError() {
            if (this.errors.has('email')) {
                if (this.errors.firstByRule('email', 'inuse')) {
                    return this.errors.firstByRule('email', 'inuse');
                } else {
                    return this.errors.firstByRule('email', 'required')
                        ? 'Email is required.'
                        : 'That is not a valid email address.';
                }
            }
        },
        passwordError() {
            if (this.errors.has('password')) {
                return this.errors.firstByRule('password', 'required')
                    ? 'Password is required.'
                    : 'Password needs minimum of 6 characters.';
            }
        },
        termsError() {
            if (this.errors.has('terms')) {
                return this.errors.firstByRule('terms', 'required')
                    ? 'Please agree to terms and privacy policy.'
                    : '';
            }
        },
        title() {
            return `Now serving patients<br/>in ${App.Util.misc.getState(this.State('getstarted.zipValidation.state'))}.`;
        }
    },

    methods: {
        generateTrackingData(mode) {
            switch(mode) {
                case 'facebook': return JSON.stringify({ account_created: false, facebook_connect: true });
                case 'form': return JSON.stringify({ account_created: false, facebook_connect: false });
                default: return null;
            }
        },

        facebookSignup() {
            if(!this.State('getstarted.userPost.terms')) {
                this.errors.add('terms', 'error', 'required');
                this.errors.first('terms:required');
            } else {
                // If a user signs up with Facebook successfully but does not finish the signup funnel
                // and then logs out, their account has been created but the signup_mode data still
                // exists. If they click the Facebook Signup again, it will log them in and send them
                // to the welcome step but we do not want to fire 'Account Created' again.
                if (!this.State('getstarted.signupMode.account_created')) {
                    App.Util.data.toStorage('signup_mode', this.generateTrackingData('facebook'));
                }
                window.location.href = `/auth/facebook?zip=${this.State('getstarted.userPost.zip')}`;
            }
        },
        onSubmit() {
            this.State('getstarted.userPost').terms = this.State('getstarted.userPost.terms') ? true : '';
            // Validate the form
            this.$validator.validateAll(this.State('getstarted').userPost).then(response => {
                if (!response) return;

                this.isProcessing = true;

                // create the user
                axios.post('api/v1/users', this.State('getstarted.userPost'))
                    .then(this.login)
                    // Error catch for user patch
                    // The BE checks for invalid zipcodes based on states we know we cannot operate in
                    // and also Iggbo servicing data.
                    // If such a zipcode is entered, the users api will return a 400
                    .catch(error => {
                        if (error.response) {
                            console.error(error.response);
                            this.responseErrors = error.response.data.errors;
                            const errorDetail = this.responseErrors[0].detail;
                            const errorType = this.responseErrors[0].type;

                            // check for different error type responses
                            if(errorType === 'email-in-use') {
                                this.errors.add('email', errorDetail.message, 'inuse');
                                this.errors.first('email:inuse');

                                // reset the submission to allow for another attempt
                                this.isProcessing = false;
                                this.isComplete = false;
                            }
                        }
                    });

                // Error catch for vee-validate of signup form fields
                }).catch(() => {
                    console.error('There are errors in the signup form fields.');
                });
        },

        // Logs the user in, triggers analytics, refreshes the page for Laravel object
        login(response) {
            axios.post('login', {
                email: this.State('getstarted.userPost.email'),
                password: this.State('getstarted.userPost.password')
            })
            .then(() => {
                this.isComplete = true;
                this.zipInRange = true;

                App.Config.user.info = response.data.data.attributes;
                App.Config.user.info.id = response.data.data.id;
                App.Config.user.info.signedIn = true;

                // Manually change this value so the Welcome step properly filters
                // the practitioner list
                App.Config.user.isPatient = true;

                // remove sign-up form local storage items on sign up
                // needed if you decide to sign up multiple acounts on one browser
                App.Util.data.killStorage(['first_name', 'last_name', 'email', 'password']);

                // In case a user initially decides to sign in with Facebook but does not follow
                // through and instead signs up via the form. We don't want the Welcome component
                // to fire 'Account Created' again.
                App.Util.data.toStorage('signup_mode', this.generateTrackingData('form'));

                App.Router.push('welcome');
            })
            .catch(error => {
                if (error.response) {
                    console.warn(error.response);
                }
            });
        }
    },

    beforeMount() {
        if (App.Config.user.isLoggedIn || App.Config.user.info.signedIn) {
            App.Router.push('welcome');
        }
    },
    mounted () {
        analytics.page("Signup");
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .top-space {
        margin-top: 5%;
    }

    .quote-container {
        @include vertical-center-absolute;
    }

    @media screen and (max-width: 1100px) {
        .hide-icon {
            display: none;
        }
    }
</style>
