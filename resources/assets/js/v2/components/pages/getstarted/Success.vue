<template>
    <SlideIn v-if="State('getstarted.signup.hasCompletedSignup')" class="ph2 ph3-m pv4">
        <Card class="mha mw6">
            <CardContent class="tc pa4">
                <SlideIn>
                    <Icon :icon="'signup_success'" :height="'100px'" :width="'100px'" />
                </SlideIn>
                <Spacer isBottom :size="3" />
                <Heading1 doesExpand>Appointment Confirmed!</Heading1>
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'" :size="'large'">
                    Dr. {{ State('getstarted.signup.practitionerName') }}, ND<br>
                    {{ appointmentDate | toDate }}<br/>
                    {{ appointmentDate | toTime }} {{ Config.time.zoneAbbr }}
                </Paragraph>
                <Spacer isBottom :size="2" />
                <AddEventButton :config="calendarConfig" />
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'">
                    You must complete the patient intake form (below) before talking with your doctor. We will send you text and email reminders before your appointment. You can with us on this screen if you have any questions.
                </Paragraph>
                <Spacer isBottom :size="4" />
                <Grid :flexAt="'m'" :columns="[{m:'1of2'}, {m:'1of2'}]" :gutters="{s:2, m:3}">
                    <div :slot="1" class="tr-m">
                        <InputButton
                            :href="'/dashboard'"
                            :mode="'gray'"
                            :text="'Dashboard'"
                            :width="'200px'"
                        />
                    </div>
                    <div :slot="2" class="tl-m">
                        <InputButton
                            :onClick="showIntakeModal"
                            :text="'Start Intake Form'"
                            :width="'200px'"
                        />
                    </div>
                </Grid>
            </CardContent>
        </Card>

        <Overlay :isActive="showModal" />
        <Modal :active="showModal" isSimple :on-close="() => showModal = false">
            <div :slot="'content'" class="tc">
                <Heading2>You are leaving Harvey</Heading2>
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'">
                    Your patient intake will be conducted by a third-party HIPAA-compliant EMR provider called &ldquo;IntakeQ&rdquo;. When prompted, enter your full name and the same email you used to sign up for Harvey. If you close the form you can come back to it later.
                </Paragraph>
                <Spacer isBottom :size="4" />
                <InputButton
                    :href="Config.user.intakeLink"
                    :text="'Go to IntakeQ'"
                    :width="'200px'"
                />
                <Spacer isBottom :size="3" />
            </div>
        </Modal>

    </SlideIn>
</template>

<script>
import moment from 'moment';

import { Icon } from 'icons';
import { AddEventButton, InputButton } from 'inputs';
import { Card, CardContent, Grid, Modal, Overlay, SlideIn, Spacer } from 'layout';
import { Heading1, Heading2, Paragraph } from 'typography';

export default {
    name: 'success',
    components: {
        AddEventButton,
        Card,
        CardContent,
        Heading1,
        Heading2,
        Icon,
        InputButton,
        Grid,
        Modal,
        Overlay,
        Paragraph,
        SlideIn,
        Spacer
    },
    data() {
        return {
            showModal: false,
            appointmentDate: this.State('getstarted.signup.data.appointment_at'),
            appointmentInformation: this.State('getstarted.signup.data')
        };
    },
    computed: {
      calendarConfig() {
          const info = this.State('getstarted.signup');
          return {
              start: App.Util.time.toLocal(info.data.appointment_at, 'MM/DD/YYYY hh:mm A'),
              end: moment.utc(info.data.appointment_at).add(60, 'm').local().format('MM/DD/YYYY hh:mm A'),
              timezone: '',
              location: '',
              title: `Appointment with Dr. ${info.practitionerName}, ND`,
              description: `Your Google Meet Link: ${info.googleMeetLink || ''}`
          }
      },
      time() {
          const timeObject = this.appointmentDate.format('h:mm a');
          return timeObject;
      },
      date() {
          const dateObject = this.appointmentDate;
          return dateObject;
      }
    },
    methods: {
        showIntakeModal() {
            this.showModal = true;
        },
        sendToIntake() {
            if (this.$root.isOnProduction()) {
                // place intake tracking here
            }
        }
    },
    filters: {
        toDate(date) {
            return moment.utc(date).local().format('dddd, MMMM Do');
        },
        toTime(date) {
            return moment.utc(date).local().format('h:mm a');
        }
    },
    beforeMount() {
        window.onbeforeunload = null;
        App.Logic.getstarted.refuseStepSkip.call(this, 'success');
    },
    mounted () {
        analytics.page('Success');
    },
};
</script>
