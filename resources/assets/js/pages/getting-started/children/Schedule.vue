<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="signup-stage-instructions">
      <StagesNav :current="'schedule'" />
      <h2>Choose date and time...</h2>
      <p>Tell us the best date and time you would like to schedule a phone consultation with your doctor. Remember, this is a <strong>virtual</strong> meeting.</p>
    </div>
    <div class="signup-container signup-stage-container signup-schedule-container">
      <router-link class="signup-back-button" :to="{ name: this.prevStage.name, path: '/' + this.prevStage.name }"><i class="fa fa-arrow-left"></i> {{ this.prevStage.display }}</router-link>

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
                  :class="{ 'available': dayObj !== null && dayObj.times.length, 'selected': selectedWeek === i && selectedDay === key }"
              ></li>
            </ol>
          </div>

        </div>
        <div class="schedule-section schedule-times" ref="timeBox">
          <h3>Choose time</h3>
          <p class="schedule-info-text" v-show="selectedDate">{{ selectedDate | fullDate }}</p>

          <ol v-show="selectedDate">
            <li v-for="(time, j) in availableTimes"
                :class="{ 'available': true, 'selected': selectedTime === j }"
                @click="handleSelectTime(time, j)"
            >{{ time | timeDisplay }}</li>
          </ol>

          <p class="schedule-info-text">Time Zone: {{ $root.addTimezone() }}</p>
        </div>
      </div>

      <p class="text-centered">Please note, all times are listed in <strong>{{ $root.addTimezone() }}</strong>. Please allow 60 minutes or longer for appointments.</p>

      <p class="error-text" v-html="errorText" v-show="errorText" style="display:block"></p>

      <button class="button button--blue" style="width: 160px" :disabled="processing" @click="checkAppointment">
        <span v-if="!processing">Continue</span>
        <LoadingBubbles v-else-if="processing" :style="{ width: '12px', fill: 'white' }" />
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
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
      },
      errorText: null,
      processing: false,
      weeks: 4,
      weekStart: moment().startOf('week')
    }
  },
  filters: {
    fullDate(value) {
      return moment(value).format('dddd, MMMM Do');
    },
    timeDisplay(value) {
      return moment(value)
        .format('h:mm a')
        .replace(/[m ]*/g,'')
        .replace(/:00/,'');
    },
    weekDay(value) {
      return moment(value).format('MMM D');
    }
  },
  computed: {
    availableTimes() {
      return this.$root.$data.signup.availableTimes;
    },
    prevStage() {
      return Laravel.user.phone_verified_at
        ? { name: 'practitioner', display: 'Practitioner' }
        : { name: 'phone', display: 'Phone' };
    },
    selectedWeek() {
      return this.$root.$data.signup.selectedWeek;
    },
    selectedDate() {
      return this.$root.$data.signup.selectedDate;
    },
    selectedDay() {
      return this.$root.$data.signup.selectedDay;
    },
    selectedTime() {
      return this.$root.$data.signup.selectedTime;
    },
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
    checkAppointment() {
      this.errorText = null;
      if (this.selectedTime === null) {
        this.errorText = 'Please select a valid date and time.';
        return;
      }
      this.processing = true;
      this.$router.push({ name: 'confirmation', path: 'confirmation' });
    },
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
      if (dayObj && dayObj.times.length) {
        // reset
        this.$root.$data.signup.selectedTime = null;
        this.$root.$data.signup.selectedDate = null;

        if (window.outerWidth < 641) this.$refs.timeBox.scrollIntoView();

        this.$root.$data.signup.selectedWeek = index;
        this.$root.$data.signup.selectedDate = dayObj.date;
        this.$root.$data.signup.selectedDay = day;
        this.$root.$data.signup.availableTimes = dayObj.times;
      }
    },
    handleSelectTime(time, index) {
      this.$root.$data.signup.selectedTime = index;
      this.$root.$data.signup.data.appointment_at = moment(time).utc().format('YYYY-MM-DD HH:mm:ss');
      // console.log(JSON.stringify(this.$root.$data.signup.data, null, 2));
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
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('schedule');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
