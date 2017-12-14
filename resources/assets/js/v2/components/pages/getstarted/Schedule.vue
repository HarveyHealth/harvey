<template>
    <SlideIn v-if="!State('getstarted.signup.hasCompletedSignup') && doctorInfo" class="ph2 ph3-m pv4">

        <div class="mha mw6 tc">
            <Heading1 doesExpand :color="'light'">Choose Date &amp; Time</Heading1>
            <Spacer isBottom :size="2" />
            <Paragraph :color="'light'" :weight="'thin'">
                Tell us the best date and time to schedule a video consultation with Dr. {{ doctorInfo.name }}, ND. You can book it 2 days from now, or as far out as 4 weeks.
            </Paragraph>
        </div>

        <Spacer isBottom :size="3" />
        <Pagination :step="'schedule'" class="mha mw7" />
        <Spacer isBottom :size="1" />

        <Card class="mha mw7">
            <CardContent class="pa0">
                <Grid :columns="[{m:6},{m:6}]" :gutters="{s:3}">
                    <div :slot="1" class="bg-gray-0 pa2 pa3-m">
                        <Heading3 :color="'muted'" class="tc uppercase">Choose Date</Heading3>
                        <Spacer isBottom :size="3" />
                        <DateSelector :weekData="weekData" :onSelect="() => scrollToSubmit()" />
                    </div>
                    <div :slot="2" class="bg-gray-0 pa2 pa3-m relative">
                        <div v-if="!selectedDate" class="doctor-container">
                            <ScheduleDoctorInfo v-if="doctorInfo" :doctorInfo="doctorInfo" />
                        </div>
                        <div v-else>
                            <Heading3 :color="'muted'" class="tc uppercase">Choose Time</Heading3>
                            <Spacer isBottom :size="3" />
                            <div class="alt f6 fw3 tc">Time Zone: {{ Config.time.zoneAbbr }}</div>
                            <TimeSelector :timeData="State('getstarted.signup.selectedTimes')" />
                        </div>
                    </div>
                </Grid>
                <div class="tc" ref="submit">
                    <Paragraph v-if="selectedDate" :weight="'thin'">
                        Your consultation will be on <span class="bg-gray-0 fw5">{{ selectedDate | fullDate }}</span>.
                    </Paragraph>
                    <Paragraph v-else :weight="'thin'">
                        Please select a date and time.
                    </Paragraph>
                    <Spacer isBottom :size="3" />
                    <InputButton
                        :isDisabled="!State('getstarted.signup.data.appointment_at') || appointmentIsSelected"
                        :isDone="appointmentIsSelected"
                        :isProcessing="isProcessing"
                        :onClick="handleAppointmentSelection"
                        :text="'Continue'"
                        :width="'160px'"
                    />
                </div>
            </CardContent>
        </Card>
    </SlideIn>
</template>

<script>
import moment from 'moment';

import { InputButton } from 'inputs';
import { Card, CardContent, Grid, SlideIn, Spacer } from 'layout';
import { Heading1, Heading3, Paragraph } from 'typography';

import DateSelector from './DateSelector.vue';
import Pagination from './Pagination.vue';
import ScheduleDoctorInfo from './ScheduleDoctorInfo.vue';
import TimeSelector from './TimeSelector.vue';

export default {
  name: 'schedule',
  components: {
      Card,
      CardContent,
      DateSelector,
      Grid,
      Heading1,
      Heading3,
      InputButton,
      Pagination,
      Paragraph,
      ScheduleDoctorInfo,
      SlideIn,
      Spacer,
      TimeSelector
  },
  data() {
      return {
          isProcessing: false,
          weeks: 4,
          weekStart: moment().startOf('week')
      };
    },
    computed: {
        appointmentIsSelected() {
            return this.State('getstarted.signup.appointmentIsSelected');
        },
        doctorInfo() {
            return this.State('getstarted.signup.practitioner');
        },
        selectedDate() {
            return this.State('getstarted.signup.selectedDate');
        },
        weekData() {
            const list = this.State('practitioners.availability.data');
            const weeks = [];

            for (let i = 1; i <= this.weeks; i++) {
                weeks.push(this.createWeek(this.weekStart));
            }

            list.forEach(dayObj => {
                // Cycle through week objects and compare with date
                weeks.forEach(weekObj => {
                    if (this.dayWithWeek(dayObj.date, weekObj.start)) {
                        weekObj.days[dayObj.day.substring(0,3)] = {
                            date: dayObj.date,
                            times: dayObj.times.map(t => t.stored)
                        };
                    }
                });
            });
            return weeks;
        }
    },
    methods: {
        createWeek(start) {
            return {
                start: start.add(1, 'days').format('YYYY-MM-DD'),
                end: start.add(6, 'days').format('YYYY-MM-DD'),
                days: { Mon: null, Tue: null, Wed: null, Thu: null, Fri: null, Sat: null, Sun: null }
            };
        },
        dayWithWeek(date, start) {
            return moment(date).startOf('week').add(1, 'days').format('YYYY-MM-DD') === start;
        },
        handleAppointmentSelection() {
            this.isProcessing = true;
            setTimeout(() => {
                App.setState({
                    'getstarted.signup.stepsCompleted.schedule': true,
                    'getstarted.signup.appointmentIsSelected': true
                });
                App.Logic.getstarted.nextStep.call(this, 'schedule');
            }, 500);
        },
        scrollToSubmit() {
            Vue.nextTick(() =>{
                if (window.innerWidth <= App.Config.misc.breakpoints.m) {
                    window.scroll({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            });
        }
    },
    beforeMount() {
        App.Logic.getstarted.refuseStepSkip.call(this, 'schedule');
    },
    mounted () {
        App.Logic.getstarted.redirectDashboard();
        window.scroll(0, 0);
        analytics.page('Schedule');
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .doctor-container {
        @include query(md) {
            @include vertical-center-absolute;
            transform: translate(-50%, -65%);
        }
    }
</style>
