<template>
  <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
    <div class="vertical-center tc">
      <div class="signup-stage-instructions color-white">
        <h2 class="heading-1 color-white">Choose Date &amp; Time</h2>
        <p>Tell us the best date and time to schedule a video consultation with your doctor. You can book it 2 days from now, or as far out as 4 weeks.</p>
      </div>
      <div class="signup-container large router">
        <router-link class="signup-back-button" :to="{ name: prevStage.name, path: '/' + prevStage.name }">
          <i class="fa fa-long-arrow-left"></i>
          <span class="font-sm">{{ prevStage.display }}</span>
        </router-link>
        <div class="signup-schedule-wrapper cf">
          <div class="schedule-section schedule-days">
            <h3 class="heading-2 font-normal font-centered">Choose Date</h3>
            <div v-for="(week, i) in weekData" class="schedule-week" v-show="hasAvailableDays(week.days)">
              <div class="schedule-week-info copy-muted">
                <span class="week font-xs">{{ weekReference(i) }}</span>
                <span class="dates font-xs">{{ week.start | weekDay }} - {{ week.end | weekDay }}</span>
              </div>
              <ol>
                <li v-for="(dayObj, key) in week.days"
                    v-show="dayObj !== null"
                    v-text="key"
                    @click="handleSelectDay(i, key, dayObj)"
                    :class="{ 'available': dayObj !== null && dayObj.times.length, 'selected': selectedWeek === i && selectedDay === key }"
                ></li>
              </ol>
            </div>
          </div>
          <div class="schedule-section schedule-times" ref="timeBox">
            <h3 class="heading-2 font-normal font-centered">Choose Time</h3>
            <h4 class="schedule-info-text heading-3" v-show="selectedDate">{{ selectedDate | fullDate }}</h4>
            <p class="time-zone font-xs font-centered font-normal">Time Zone: {{ $root.addTimezone() }}</p>
            <ol v-show="selectedDate">
              <li v-for="(time, j) in availableTimes"
                  :class="{ 'available': true, 'selected': selectedTime === j }"
                  @click="handleSelectTime(time, j)"
              >{{ time | timeDisplay }}</li>
            </ol>
            <div v-if="!selectedDate" class="left-arrow"><i class="fa fa-hand-o-left" aria-hidden="true"></i></div>
          </div>
        </div>
        <p class="closing-selection" v-if="selectedDate" v-show="!errorText" >
          Your consultation will be on <span class="font-bold">{{ selectedDate | fullDate }}.</span>
        </p>
        <p class="copy-error" v-html="errorText" v-show="errorText" style="margin-bottom: 12px;"></p>
        <button class="button button--blue" style="width: 160px" @click="checkAppointment">Continue</button>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: 'schedule',
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
        'pad-md': true,
        'flex-wrapper': true,
        'height-100': true,
        'justify-center': true
      },
      errorText: null,
      isProcessing: false,
      weeks: 4,
      weekStart: moment().startOf('week')
    };
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
            };
          }
        });
      });
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
      this.isProcessing = true;
      this.$router.push({ name: 'payment', path: '/payment' });
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
    },
    hasAvailableDays(days) {
      let pass = false;
      for (let day in days) {
        if (days[day] !== null) pass = true;
      }
      return pass;
    },
    weekReference(index) {
      switch (index) {
        case 0:
          return 'This week';
        case 1:
          return 'Next week';
        case 2:
          return 'The week after';
        case 3:
          return 'In three weeks';
      }
    }
  },
  mounted () {
    window.scroll(0, 0);
    this.$root.toDashboard();
    this.$root.$data.signup.visistedStages.push('schedule');
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);

    analytics.page('Schedule');
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
};
</script>
