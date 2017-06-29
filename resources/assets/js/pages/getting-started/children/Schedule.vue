<template>
  <div :class="containerClasses">
    <div class="signup-stage-instructions">
      <StagesNav :current="'schedule'" />
      <h2>Choose date and time...</h2>
      <p>Tell us the best date and time you would like to schedule a phone consultation with your doctor. Remember, this is a <strong>virtual</strong> meeting.</p>
    </div>
    <div class="signup-container signup-stage-container signup-schedule-container">
      <router-link class="signup-back-button" :to="{ name: 'phone', path: '/phone' }"><i class="fa fa-arrow-left"></i> Phone</router-link>

      <div class="signup-schedule-wrapper cf">
        <div class="schedule-section schedule-days">
          <h3>Choose date</h3>
          <div class="schedule-week">
            <div class="schedule-week-info">
              <span class="week">This week</span>
              <span class="dates">June 26 - June 30</span>
            </div>
            <ol>
              <li>Mon</li>
              <li>Tue</li>
              <li>Wed</li>
              <li class="available">Thu</li>
              <li class="selected">Fri</li>
              <li>Sat</li>
              <li>Sun</li>
            </ol>
          </div>
        </div>
        <div class="schedule-section schedule-times">
          <h3>Choose time</h3>
          <ol>
            <li class="available">9a</li>
            <li class="available">10a</li>
            <li class="available">10:30a</li>
            <li class="available">11p</li>
            <li class="available">11:30p</li>
          </ol>
          <p class="schedule-timezone">Time Zone: {{ $root.addTimezone() }}</p>
        </div>
      </div>

      <p class="text-centered">Please note, all times are listed in <strong>{{ $root.addTimezone() }}</strong>. Please allow 60 minutes or longer for appointments.</p>

      <button class="button button--blue" style="width: 160px" :disabled="processing">
        <span v-if="!processing">Continue</span>
        <LoadingBubbles v-else-if="processing" :style="{ width: '16px', fill: 'white' }" />
      </button>

    </div>
    <div :data-test="JSON.stringify(this.availability, null, 2)"></div>
  </div>
</template>

<script>
import LoadingBubbles from '../../../commons/LoadingBubbles.vue';
import StagesNav from '../util/StagesNav.vue';

import moment from 'moment';

export default {
  name: 'schedule',
  components: {
    StagesNav
  },
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      processing: false,
      weeks: 4,
      weekStart: moment().startOf('week')
    }
  },
  computed: {
    availability() {
      const list = this.$root.$data.signup.availability;
      const weeks = [];
      for (let i = 1; i <= this.weeks; i++) {
        weeks.push(this.createWeek(this.weekStart));
      }
      list.forEach(dayObj => {
        // Cycle through week objects and compare with date
        weeks.forEach(weekObj => {
          if (this.dayWithWeek(dayObj.date, weekObj.start)) {
            weekObj.days[dayObj.day.substring(0,3)] = dayObj.times.map(t => t.stored)
          }
        })
      })
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
    }
  },
  mounted () {
    this.$root.$data.signup.visistedStages.push('schedule');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
