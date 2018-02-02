<template>
    <SlideIn v-if="State.getstarted.signup.hasCompletedSignup" class="ph2 ph3-m pv4">
        <Card class="mha mw6">
            <CardContent class="tc pa4">
                <SlideIn>
                    <Icon :icon="'signup_success'" :height="'100px'" :width="'100px'" />
                </SlideIn>
                <Spacer isBottom :size="3" />
                <Heading1 doesExpand>Appointment Confirmed!</Heading1>
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'" :size="'large'">
                    Dr. {{ State.getstarted.signup.practitionerName }}, ND<br>
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
                <Grid :columns="[{m:6}, {m:6}]" :gutters="{s:2, m:3}">
                    <div :slot="1" class="tr-m">
                        <InputButton
                            :href="'/dashboard'"
                            :mode="'secondary'"
                            :text="'Dashboard'"
                            :width="'200px'"
                        />
                    </div>
                    <div :slot="2" class="tl-m">
                        <InputButton
                            :href="'/intake'"
                            :text="'Start Intake Form'"
                            :width="'200px'"
                        />
                    </div>
                </Grid>
            </CardContent>
        </Card>
    </SlideIn>
</template>

<script>
import moment from 'moment';

import { Icon } from 'icons';
import { AddEventButton, InputButton } from 'inputs';
import { Card, CardContent, Grid, SlideIn, Spacer } from 'layout';
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
        Paragraph,
        SlideIn,
        Spacer
    },
    data() {
        return {
            appointmentDate: this.State.getstarted.signup.data.appointment_at,
            appointmentInformation: this.State.getstarted.signup.data
        };
    },
    computed: {
      calendarConfig() {
          const info = this.State.getstarted.signup;
          return {
              start: App.Util.time.toLocal(info.data.appointment_at, 'MM/DD/YYYY hh:mm A'),
              end: moment.utc(info.data.appointment_at).add(60, 'm').local().format('MM/DD/YYYY hh:mm A'),
              timezone: '',
              location: '',
              title: `Appointment with Dr. ${info.practitionerName}, ND`,
              description: `Your Google Meet Link: ${info.googleMeetLink || ''}`
          };
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
        window.datacoral('trackPageView');
    }
};
</script>
