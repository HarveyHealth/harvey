<template>
  <div>
    <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
      </ul>

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>
    </div>
    <div class="container large">
      <div class="signup-form-container large">
        <div class="flex-wrapper">
          <div class="input-wrap calendar-block">
            <div class="calendar-block_container">
              <h2 class="header-large text-centered">Choose Date</h2>
              <div class="calendar-week-container">
                <div class="calendar-week-container_title-wrapper">
                  <h3 class="calendar-week-container_title">This week</h3>
                  <span class="calendar-week-container_date">March 27 - April 2nd</span>
                </div>

                <date-picker
                  :selected-date="selectedDate"
                  :maximum-days="maximumDays"
                  :start-date-time="startDateTime"
                />

              </div>
              <!-- <div class="calendar-week-container">
                <div class="calendar-week-container_title-wrapper">
                  <h3 class="calendar-week-container_title">Next week</h3>
                  <span class="calendar-week-container_date">March 27 - April 2nd</span>
                </div>
              </div> -->
            </div>
          </div>

          <div class="input-wrap calendar-block">
            <div class="calendar-block_container">
              <h2 class="header-large text-centered">Choose Time</h2>

              <time-picker
                :selected-date="selectedDate"
                :selected-time="selectedTime"
                :now="now"
                :start-of-day-hour="startOfDayHour"
                :end-of-day-hour="endOfDayHour"
                :minimum-notice="minimumNotice"
                :duration="duration"
                :start-date-time="startDateTime"
              />

              <p class="text-centered small">Time Zone: PST</p>
            </div>
          </div>
        </div>
<!--
        <div class="calendar-notice text-centered">
          <p class="large">This will replace existing appointment on:  Thursday, March 30th at 5:00pm</p>
        </div>
-->
        <div class="text-centered">
          <!-- <a class="button" @click.prevent="nextStep">Continue</a> -->
          <input type="submit" class="button" value="Book Now">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  import DatePicker from './_DatePicker.vue';
  import TimePicker from './_TimePicker.vue';

  export default {
    name: 'DateTime',
    data() {
      return {
        title: 'Choose date and time',
        subtitle: 'Lastly, tell us the best date and time you would like to schedule a 45-60 minute phone consultation with your chosen physician.',

        // Date/Time Data
        now: moment(),
        startOfDayHour: 9,
        endOfDayHour: 18,
        maximumDays: 7,
        minimumNotice: 0,
        duration: 1,

        selectedDate: moment(),
        selectedTime: moment(),
      }
    },
    components: {
      DatePicker,
      TimePicker,
    },
    methods: {
      canBookToday() {
        const acceptableTime = moment(this.now).add(this.minimumNotice, 'hours');
        const endOfDayTime = moment(this.now).set({hour: this.endOfDayHour, minute: 0, second: 0, millisecond: 0}).subtract(this.duration, 'hours');

        return acceptableTime <= endOfDayTime;
      },

      getNearestTime(_time, _interval) {
        let minutes = Math.ceil(Math.max(1, _time.minutes()) / _interval) * _interval,
            hours = _time.hours();

        if (minutes == 60) {
          hours ++;
          minutes = 0;

          if (hours >= 24) {
            hours = hours - 24;
          }
        }

        return hours;
      },

      onDateTimeChange(_obj) {
        if (_obj.type === 'date') {
          this.selectedDate = _obj.value;
        } else if(_obj.type === 'time') {
          this.selectedTime = _obj.value;
        }
      },
    },
    computed: {
      startDateTime() {
        const canBookToday = this.canBookToday();

        if (canBookToday) {
          const hour = this.getNearestTime(this.now, 60) + this.minimumNotice;
          return this.now.set({hour: hour, minute: 0, second: 0, millisecond: 0}).utc();
        } else {
          return this.now.add(1, 'days').set({hour: this.startOfDayHour, minute: 0, second: 0, millisecond: 0}).utc();
        }
      },
    },
    mounted() {
      this.$eventHub.$on('datetime-change', this.onDateTimeChange);
    }
  }
</script>

<style>

</style>
