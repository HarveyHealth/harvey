<template>
    <SlideIn v-if="!State('getstarted.signup.hasCompletedSignup')" class="ph2 ph3-m pv4">
        <div class="mha mw6 tc">
            <Heading1 doesExpand :color="'light'">{{ preface.heading }}</Heading1>
            <Spacer isBottom :size="2" />
            <Paragraph :color="'light'" :weight="'thin'">{{ preface.subtext }}</Paragraph>
        </div>
        <Spacer isBottom :size="3" />
        <Pagination :step="'phone'" class="mha mw6" />
        <Spacer isBottom :size="1" />
        <Card class="mha mw6">
            <CardContent class="tc ph4 pv5">
                <Icon :fill="'gray-4'" :icon="icon" :height="'100px'" :width="'100px'" :style="iconStyle" />
                <Spacer isBottom :size="4" />
                <PhoneValidation />
            </CardContent>
        </Card>
    </SlideIn>
</template>

<script>
import { Icon } from 'icons';
import { Card, CardContent, SlideIn, Spacer } from 'layout';
import { Heading1, Paragraph } from 'typography';

import Pagination from './Pagination.vue';
import PhoneValidation from './PhoneValidation.vue';

export default {
    name: 'phone',
    components: {
        Card,
        CardContent,
        Heading1,
        Icon,
        Pagination,
        Paragraph,
        PhoneValidation,
        SlideIn,
        Spacer
    },
    computed: {
        hasPhone() {
            return this.State('getstarted.signup.phone');
        },
        icon() {
            return this.hasPhone ? 'phone_sms' : 'phone';
        },
        iconStyle() {
            return this.hasPhone ? { position: 'relative', left: '12px' } : '';
        },
        isComplete() {
            return this.State('getstarted.signup.stepsCompleted.phone');
        },
        phoneIsVerified() {
            return this.State('getstarted.signup.phoneVerifiedDate');
        },
        preface() {
            if (this.State('getstarted.signup.stepsCompleted.phone')) {
                return {
                    heading: 'Phone Confirmed!',
                    subtext: 'Your phone number has been confirmed. You can change your phone number, or continue to the scheduling step.'
                };
            } else if (this.hasPhone) {
                return {
                    heading: 'Enter Confirmation Code',
                    subtext: 'Please enter the confirmation code that was just sent to you via text message. You can click "Text Me Again" if you didn’t receive it.'
                };
            } else {
                return {
                    heading: 'Validate Phone Number',
                    subtext: 'Our doctors require a valid phone number on file for every patient. We will also send you text reminders before every appointment.'
                };
            }
        }
    },
    beforeMount() {
        App.Logic.getstarted.refuseStepSkip.call(this, 'phone');
        App.setState({
            'getstarted.signup.phone': this.Config.user.info.phone,
            'getstarted.signup.phoneVerifiedDate': this.Config.user.info.phone_verified_at
        });
        if (this.State('getstarted.signup.phoneVerifiedDate') && !this.isComplete) {
            App.setState({
                'getstarted.signup.stepsCompleted.phone': true
            });
            App.Logic.getstarted.nextStep.call(this, 'phone');
        }
    },
    mounted() {
        App.Logic.getstarted.redirectDashboard();
        window.scroll(0, 0);
        analytics.page('Phone');
    }
};
</script>
