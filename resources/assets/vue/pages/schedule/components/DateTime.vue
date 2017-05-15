<template>
  <div :class="animClasses">
    <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step" v-on:click="firstStep"></li>
        <li class="signup_progress-step" v-on:click="previousStep"></li>
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

              <day-picker
                :selected-date="selectedDate"
                :maximum-days="maximumDays"
                :start-date-time="startDateTime"
                :availability="availability"
                :weekOffset="0"
              />

              <day-picker
                :selected-date="selectedDate"
                :maximum-days="maximumDays"
                :start-date-time="startDateTime"
                :availability="availability"
                :weekOffset="7"
              />

            </div>
          </div>

          <div class="input-wrap calendar-block">
            <div class="calendar-block_container">
              <h2 class="header-large text-centered">Choose Time</h2>

              <time-picker
                :availability="availability"
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

        <div class="text-centered">

          <input
            class="button"
            type="submit"
            value="Confirm Appointment"
            :disabled="dateSelected && timeSelected ? false : true"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  import DayPicker from './_DayPicker.vue';
  import TimePicker from './_TimePicker.vue';

  export default {
    name: 'DateTime',
    props: ['availability'],
    data() {
      return {
        title: 'Choose date and time',
        subtitle: 'Lastly, tell us the best date and time you would like to schedule a 45-60 minute phone consultation with your chosen physician.',
        selectedAppointmentDate: moment(),

        // Date/Time Data
        now: moment(),
        startOfDayHour: 9,
        endOfDayHour: 18,
        maximumDays: 5,
        minimumNotice: 0,
        duration: 1,

        selectedDate: this.$parent.selectedDate || moment(),
        selectedTime: this.$parent.selectedTime || moment().add(1, 'hour'), // making sure we can't select the current hour

        // validation
        dateSelected: this.$parent.selectedDateBool,
        timeSelected: this.$parent.selectedTimeBool,

        animClasses: {
          'anim-fade-slideup': true,
          'anim-fade-slideup-in': false,
        },

      }
    },
    components: {
      DayPicker,
      TimePicker,
    },
    methods: {
      canBookToday() {
        const acceptableTime = moment(this.now).add(this.minimumNotice, 'hours');
        const endOfDayTime = moment(this.now).set({hour: this.endOfDayHour, minute: 0, second: 0, millisecond: 0}).subtract(this.duration, 'hours');

        return acceptableTime <= endOfDayTime;
      },
      previousStep() {
        this.$parent.selectedDate = this.selectedDate
        this.$parent.selectedTime = this.selectedTime
        this.$parent.previous();
      },
      firstStep() {
        this.$parent.selectedDate = this.selectedDate
        this.$parent.selectedTime = this.selectedTime
        this.$parent.previous();
        this.$parent.previous();
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
          this.$parent.selectedDate = _obj.value
          this.dateSelected = true;
          this.$parent.selectedDateBool = true;

        } else if(_obj.type === 'time') {

          this.selectedTime = _obj.value;
           this.$parent.selectedTime = _obj.value
          this.timeSelected = true;
          this.$parent.selectedTimeBool = true;

        }

        // construct a moment object from the selected parts
        this.selectedAppointmentDate = moment(
          {
            month: this.selectedDate.month(),
            day: this.selectedDate.date(),
            hour: this.selectedTime.hour(),
            minute: this.selectedTime.minute(),
          },
        );



        // this should be UTC

        this.$parent.appointmentDate = this.selectedAppointmentDate.format('YYYY-MM-DD hh:mm:ss a');
      },
    },
    computed: {
      startDateTime() {
        const canBookToday = this.canBookToday();
        let startDate = {};

        if (canBookToday) {
          const hour = this.getNearestTime(this.now, 60) + this.minimumNotice;
          startDate = this.now.set({hour: hour, minute: 0, second: 0, millisecond: 0}).utc();
        } else {
          startDate = this.now.add(1, 'days').set({hour: this.startOfDayHour, minute: 0, second: 0, millisecond: 0}).utc();
        }

        return startDate;
      },
    },
    mounted() {
      this.$eventHub.$on('datetime-change', this.onDateTimeChange);
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300)
      if (this.$parent.env === 'prod') {
        this.$ma.trackEvent({
            action: 'View Date and Time Selector',
            fb_event: 'ViewContent',
            category: 'clicks',
            properties: {
                laravel_object: Laravel.user
            },
            value: 'PageView'
        })
      }
    },
  }
</script>
