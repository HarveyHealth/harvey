<template>
    <div class="tc">
        <SlideIn :to="'right'" v-if="!phoneIsSaved">
            <form @submit.prevent>
                <TheMask
                    class="phone-input"
                    name="phone_number"
                    type="phone"
                    v-model="phoneNumber"
                    mask="(###) ###-####"
                    placeholder="Mobile Number"
                    :disabled="phoneNumberHasSent"
                    :ref="'phone_input'"
                />
                <Spacer isBottom :size="4" />
                <div v-show="isUserPatchError" class="mb2 red" v-html="phoneProcessError"></div>
                <div v-show="phoneDuplicateError" class="mb2 red" v-html="phoneDuplicateError"></div>
                <InputButton
                    :isDisabled="phoneNumber.length < 10"
                    :isProcessing="phoneNumberHasSent"
                    :onClick="handlePhoneNumber"
                    :text="'Send Text'"
                    :width="'160px'"
                />
            </form>
        </SlideIn>
        <SlideIn :to="'left'" v-if="phoneIsSaved">
            <form @submit.prevent>
                <CodeInput
                    :isDisabled="phoneCodeHasSent || phoneCodeIsConfirmed"
                    :mask="'#####'"
                    :onInput="handlePhoneCodeInput"
                    :ref="'code_input'"
                    :type="'phone'"
                />
                <Spacer isBottom :size="2" />
                <span class="button-link db f6 fw3" @click="handleCodeResend" v-if="!phoneStepIsComplete">
                    <i class="fa fa-repeat" aria-hidden="true"></i>
                    Text Me Again
                </span>
                <span class="button-link db f6 fw3" @click="handlePhoneReset">
                    Re-Enter Phone Number
                </span>
                <Spacer isBottom :size="4" />
                <div class="mb2 red" v-show="isInvalidCode" v-html="phoneCodeInvalidError"></div>
                <div class="mb2 red" v-show="isCodeProcessError" v-html="phoneCodeProcessError"></div>
                <InputButton
                    :isDisabled="phoneCode.length < 5 || phoneCodeIsConfirmed"
                    :isDone="phoneCodeIsConfirmed"
                    :isProcessing="phoneCodeHasSent"
                    :onClick="handlePhoneCode"
                    :text="'Confirm Code'"
                    :width="'160px'"
                />
            </form>
        </SlideIn>
    </div>
</template>

<script>
import { CodeInput, InputButton } from 'inputs';
import { SlideIn, Spacer } from 'layout';
import { TheMask } from 'vue-the-mask';

export default {
    components: {
        CodeInput,
        InputButton,
        TheMask,
        SlideIn,
        Spacer
    },
    data() {
        return {
            isCodeProcessError: false,
            isInvalidCode: false,
            isUserPatchError: false,
            phoneCode: this.State('getstarted.signup.phoneCode') || '',
            phoneCodeHasSent: false,
            phoneNumber: this.State('getstarted.signup.phone') || '',
            phoneNumberHasSent: false,
            phoneDuplicateError: '',
            phoneProcessError: 'There was an error processing your phone number. Please call us at <a href="tel:8006909989">800-690-9989</a> to speak with our Customer Support.',
            phoneCodeInvalidError: 'Invalid code entered.',
            phoneCodeProcessError: 'There was an error processing your confirmation number. Please try sending the code again.'
        }
    },
    computed: {
        phoneIsSaved() {
            return this.State('getstarted.signup.phone');
        },
        phoneCodeIsConfirmed() {
            return this.State('getstarted.signup.phoneCode').length > 0;
        },
        phoneStepIsComplete() {
            return this.State('getstarted.signup.stepsCompleted.phone');
        }
    },
    methods: {
        handleCodeResend() {
            this.resetErrors();
            this.phoneCode = '';
            App.setState({
                'getstarted.signup.stepsCompleted.phone': false,
                'getstarted.signup.phoneCode': ''
            });
            axios.post(`api/v1/users/${Laravel.user.id}/phone/send_verification_code`);
            Vue.nextTick(() => this.$refs.code_input.$el.focus());
        },
        handlePhoneCode() {
            this.phoneCodeHasSent = true;
            this.resetErrors();
            axios.get(`${this.Config.misc.api}users/${this.Config.user.info.id}/phone/verify?code=${this.phoneCode}`).then(response => {
                this.phoneCodeHasSent = false;
                if (response.data.verified) {
                    App.setState('getstarted.signup.phoneCode', this.phoneCode);
                    setTimeout(() => {
                        App.Logic.getstarted.nextStep.call(this, 'phone');
                    }, 500);
                } else {
                    this.isInvalidCode = true;
                }
            }).catch(error => {
                this.phoneCodeHasSent = false;
                this.isCodeProcessError = true;
            });
        },
        handlePhoneCodeInput(value) {
            this.phoneCode = value;
        },
        // Patches the user's phone number which triggers a Twilio api call
        handlePhoneNumber() {
            this.phoneNumberHasSent = true;
            this.resetErrors();
            axios.patch(`/api/v1/users/${this.Config.user.info.id}`, { phone: this.phoneNumber }).then(response => {
                this.phoneNumberHasSent = false;
                App.setState('getstarted.signup.phone', this.phoneNumber);
                Vue.nextTick(() => this.$refs.code_input.$el.focus());

                // Segment Identify update
                analytics.identify(this.Config.user.info.id, {
                    phone: number
                }, {
                    integrations: {
                        Intercom : {
                            user_hash: this.Config.user.info.intercom_hash
                        }
                    }
                });
            }).catch(error => {
                this.phoneNumberHasSent = false;
                if (error.response) {
                    this.phoneDuplicateError = error.response.data.errors[0].detail;
                }
            });
        },
        handlePhoneReset() {
            this.resetErrors();
            this.phoneCode = '';
            this.phoneNumber = '';
            App.setState({
                'getstarted.signup.stepsCompleted.phone': false,
                'getstarted.signup.phoneCode': '',
                'getstarted.signup.phone': ''
            });
            Vue.nextTick(() => this.$refs.phone_input.$el.focus());
        },
        resetErrors() {
            this.isUserPatchError = false;
            this.phoneDuplicateError = '';
            this.isInvalidCode = false;
            this.isCodeProcessError = false;
        }
    }
}
</script>

<style lang="scss">
    @import '~sass';

    .button-link {
        color: $color-accent-dark;
        cursor: pointer;
        margin: 6px auto;
        padding: 4px;
    }

    .phone-input {
        @extend %input--text-reset;
        @extend %input--bare;
    }
</style>
