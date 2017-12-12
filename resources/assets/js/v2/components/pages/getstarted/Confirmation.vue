<template>
    <SlideIn v-if="!State('getstarted.signup.hasCompletedSignup')" class="ph2 ph3-m pv4">
        <div class="mha mw6 tc">
            <Heading1 doesExpand :color="'light'">Final Confirmation</Heading1>
            <Spacer isBottom :size="2" />
            <Paragraph :color="'light'" :weight="'thin'"></Paragraph>
        </div>
        <Spacer isBottom :size="3" />
        <Pagination :step="'confirmation'" class="mha mw6" />
        <Spacer isBottom :size="1" />
        <Card class="mha mw6">
            <CardContent class="tc ph4 pv5">
                <Icon :fill="'gray-4'" :icon="'clipboard'" :height="'100px'" :width="'100px'" />
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'">You are about to book a video consultation with Dr. {{ doctor }} on {{ dateDisplay }} at {{ timeDisplay }}.<br/><br/>{{ paymentStatement }}. Out of respect for our doctors, we charge a $10 fee for <em>no shows</em> or cancelations within 6 hours of your appointment.</Paragraph>
                <Spacer isBottom :size="3" />
                <InputButton
                    :isDone="State('getstarted.signup.hasCompletedSignup')"
                    :isDisabled="isProcessing"
                    :isProcessing="isProcessing"
                    :onClick="confirmSignup"
                    :text="'Book Appointment'"
                    :width="'200px'"
                />
            </CardContent>
        </Card>

        <Overlay :isActive="showModal" />
        <Modal
            :active="showModal"
            isSimple
            :onClose="() => showModal = false"
        >
            <div :slot="'content'" class="pa2 tc">
                <Heading1 doesExpand>Booking Conflict</Heading1>
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'">
                    We&rsquo;re sorry, it looks like that date and time is no longer available. Please try another time. For general questions, please give us a call at <a href="tel:8006909989">800-690-9989</a>, or click the chat button at the bottom corner of the page.
                </Paragraph>
                <Spacer isBottom :size="4" />
                <InputButton
                    :isDisabled="State('practitioners.availability.isLoading')"
                    :isProcessing="State('practitioners.availability.isLoading')"
                    :mode="'secondary'"
                    :text="'Back to Schedule'"
                    :onClick="handleNewAvailability"
                />
            </div>
        </Modal>
    </SlideIn>
</template>

<script>
import moment from 'moment';

import { Icon } from 'icons';
import { InputButton } from 'inputs';
import { Card, CardContent, Modal, Overlay, SlideIn, Spacer } from 'layout';
import { Heading1, Paragraph } from 'typography';

import Pagination from './Pagination.vue';

export default {
    name: 'confirmation',
    components: {
        Card,
        CardContent,
        Heading1,
        Icon,
        InputButton,
        Modal,
        Overlay,
        Pagination,
        Paragraph,
        SlideIn,
        Spacer
    },
    data() {
        return {
            isProcessing: false,
            cardBrand: this.State('getstarted.signup.cardBrand') || this.Config.user.info.card_brand,
            date: this.State('getstarted.signup.data.appointment_at'),
            doctor: `${this.State('getstarted.signup.practitionerName')}, ND`,
            paymentStatement: `Your consultation will cost $150, which will be charged to your ${this.State('getstarted.signup.cardBrand') || 'card'} after the consultation`,
            phone: this.State('.getstarted.signup.phone') || this.Config.user.info.phone,
            showModal: false,
            state: this.State('getstarted.signup.practitionerState')
        };
    },
    computed: {
        dateDisplay() {
            return moment.utc(this.date).local().format('dddd, MMMM Do');
        },
        timeDisplay() {
            return this.$root.addTimezone(moment.utc(this.date).local().format('h:mm a'));
        },
        phoneDisplay() {
            return this.phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
    },
    methods: {
        confirmSignup() {
            this.isProcessing = true;
            // Remove discount_code from sent data if it does not exist
            if (!this.State('getstarted.signup.data.discount_code')) {
                delete this.State('getstarted.signup.data').discount_code;
            }

            axios.post('/api/v1/appointments', this.State('getstarted.signup.data')).then(response => {
                this.isProcessing = false;
                analytics.track("Consultation Confirmed");
                App.Util.data.killStorage('zip_validation');
                App.setState({
                    'getstarted.signup.googleMeetLink': response.data.data.attributes.google_meet_link,
                    'getstarted.signup.stepsCompleted.confirmation': true,
                    'getstarted.signup.hasCompletedSignup': true
                });

                setTimeout(() => App.Router.push({ path: '/success' }), 800);

            })
            .catch(() => {
                // 400 Bad request means the time was booked just before the signup user confirmed but after they
                // loaded availability for their selected practitioner.
                this.showModal = true;
            });
        },
        handleNewAvailability() {
            App.Http.practitioners.getAvailability(this.State('getstarted.signup.data.practitioner_id'), availability => {
                App.setState({
                    'getstarted.signup.selectedWeek': null,
                    'getstarted.signup.selectedDay': null,
                    'getstarted.signup.selectedTime': null,
                    'getstarted.signup.selectedDate': null,
                    'getstarted.signup.stepsCompleted.schedule': false,
                    'getstarted.signup.appointmentIsSelected': false,
                    'getstarted.signup.data.appointment_at': null
                });
                App.Router.push({ path: '/schedule' });
            });
        }
    },
    beforeMount() {
        App.Logic.getstarted.refuseStepSkip.call(this, 'confirmation');
    },
    mounted () {
        window.scroll(0, 0);
        App.Logic.getstarted.redirectDashboard();
        analytics.page('Confirmation');
    }
};
</script>
