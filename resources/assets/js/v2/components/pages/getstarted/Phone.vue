<template>
    <SlideIn v-if="!State('getstarted.signup.hasCompletedSignup')" class="ph2 ph3-m pv4">
        <div class="mha mw6 tc">
            <Heading1 doesExpand :color="'light'">{{ preface.heading }}</Heading1>
            <Spacer isBottom :size="2" />
            <Paragraph :color="'light'">{{ preface.subtext }}</Paragraph>
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
import { InputButton } from 'inputs';
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
        preface() {
            return this.hasPhone
                ?   {
                        heading: 'Enter Confirmation Code',
                        subtext: 'Please enter the confirmation code that was just sent to you via text message. You can click "Text Me Again" if you didn’t receive it.'
                    }
                :   {
                        heading: 'Validate Phone Number',
                        subtext: 'Our doctors require a valid phone number on file for every patient. We will also send you text reminders before every appointment.'
                    };
        }
    },
    beforeMount() {
        App.Logic.getstarted.refuseStepSkip.call(this, 'phone');
    },
    mounted() {
        App.Logic.getstarted.redirectDashboard();
        window.scroll(0, 0);
        analytics.page('Phone');
    }
}
</script>
