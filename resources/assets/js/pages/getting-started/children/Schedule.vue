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

          <div v-for="(week, i) in weekData" class="schedule-week">
            <div class="schedule-week-info">
              <span class="week">{{ weekReference(i) }}</span>
              <span class="dates">{{ week.start | weekDay }} - {{ week.end | weekDay }}</span>
            </div>
            <ol>
              <li v-for="(dayObj, key) in week.days"
                  v-text="key"
                  @click="handleSelectDay(i, key, dayObj)"
                  :class="{ 'available': dayObj !== null, 'selected': selectedWeek === i && selectedDay === key }"
              ></li>
            </ol>
          </div>

        </div>
        <div class="schedule-section schedule-times">
          <h3>Choose time</h3>
          <p class="schedule-info-text" v-show="selectedDay">{{ selectedDate | fullDate }}</p>

          <ol>
            <li v-for="(time, j) in availableTimes"
                :class="{ 'available': true, 'selected': selectedTime === j }"
                @click="handleSelectTime(time, j)"
            >{{ time | timeDisplay }}</li>
          </ol>

          <p class="schedule-info-text">Time Zone: {{ $root.addTimezone() }}</p>
        </div>
      </div>

      <p class="text-centered">Please note, all times are listed in <strong>{{ $root.addTimezone() }}</strong>. Please allow 60 minutes or longer for appointments.</p>

      <button class="button button--blue" style="width: 160px" :disabled="processing">
        <span v-if="!processing">Continue</span>
        <LoadingBubbles v-else-if="processing" :style="{ width: '16px', fill: 'white' }" />
      </button>

    </div>
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
      availableTimes: [],
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      processing: false,
      selectedWeek: null,
      selectedDate: null,
      selectedDay: null,
      selectedTime: null,
      weeks: 4,
      weekStart: moment().startOf('week')
    }
  },
  filters: {
    fullDate(value) {
      return moment(value).format('dddd, MMMM Do');
    },
    timeDisplay(value) {
      return moment.utc(value)
        .local()
        .format('h:mm a')
        .replace(/[m ]*/g,'')
        .replace(/:00/,'');
    },
    weekDay(value) {
      return moment.utc(value).local().format('MMM D');
    }
  },
  computed: {
    weekData() {
      const list = this.$root.$data.signup.availability;
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
            }
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
    },
    handleSelectDay(index, day, dayObj) {
      this.selectedWeek = index;
      this.selectedDate = dayObj.date;
      this.selectedDay = day;
      this.availableTimes = dayObj.times;
    },
    handleSelectTime(time, index) {
      this.selectedTime = index;
      this.$root.$data.signup.data.appointment_at = moment(time).format('YYYY-MM-DD HH:mm:ss');
    },
    weekReference(index) {
      switch (index) {
        case 0:
          return 'This week';
        case 1:
          return 'Next week';
        case 2:
          return 'In two weeks';
        case 3:
          return 'In thee weeks';
      }
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
