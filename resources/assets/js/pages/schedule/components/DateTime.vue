<template>
  <div :class="animClasses">
    <div class="container small">
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step" v-on:click="firstStep"></li>
        <li class="signup_progress-step" v-on:click="previousStep"></li>
        <li class="signup_progress-step current"></li>
      </ul>
      <div class="guide-block">
        <h1 class="header-xlarge">{{ title }}</h1>
        <p class="large">{{ subtitle }}</p>
      </div>
    </div>
    <div class="container large">
      <div class="signup-form-container large">
        <div class="flex-wrapper">
          <div class="input-wrap calendar-block">
            <div class="calendar-block_container">
              <h2 class="header-large text-centered">Choose Date</h2>

              <!-- <day-picker
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
              /> -->


              <div class="calendar-week-container">
                <div class="calendar-week-container_title-wrapper">
                  <h3 class="calendar-week-container_title">This Week</h3>
                  <span class="calendar-week-container_date">{{ week1Range }}</span>
                </div>
                <ul class="calendar-week-container_days-wrapper">
                  <li class="calendar-item" :class="{selected: selected_day === dayObj}" v-for="dayObj in week1">
                    <button class="calendar-item_link"
                            @click.prevent="selectDay(dayObj)"
                            :disabled="!dayObj.times.length">
                      <span>{{ dayObj.date | datetime('dd') }}</span>
                    </button>
                  </li>
                </ul>
              </div>

              <div class="calendar-week-container">
                <div class="calendar-week-container_title-wrapper">
                  <h3 class="calendar-week-container_title">Next Week</h3>
                  <span class="calendar-week-container_date">{{ week2Range }}</span>
                </div>
                <ul class="calendar-week-container_days-wrapper">
                  <li class="calendar-item" :class="{selected: selected_day === dayObj}" v-for="dayObj in week2">
                    <button class="calendar-item_link"
                            @click.prevent="selectDay(dayObj)"
                            :disabled="!dayObj.times.length">
                      <span>{{ dayObj.date | datetime('dd') }}</span>
                    </button>
                  </li>
                </ul>
              </div>

            </div>
          </div>

          <div class="input-wrap calendar-block">
            <div class="calendar-block_container">
              <h2 class="header-large text-centered">Choose Time</h2>

              <!-- <time-picker
                :availability="availability"
                :selected-date="selectedDate"
                :selected-time="selectedTime"
                :now="now"
                :start-of-day-hour="startOfDayHour"
                :end-of-day-hour="endOfDayHour"
                :minimum-notice="minimumNotice"
                :duration="duration"
                :start-date-time="startDateTime"
              /> -->

              <div class="calendar-week-container">
                <ul class="calendar-week-container_days-wrapper" v-if="selected_day">
                  <li class="calendar-item bar"
                      v-for="(timeObj, i) in selected_day.times"
                      :class="{selected: selected_time === selected_day.times[i].stored}">
                    <button class="calendar-item_link" @click.prevent="selectTime(i)">
                      {{ timeObj.stored | toLocal }}
                    </button>
                  </li>
                </ul>
                <p class="text-centered" v-if="!selected_day">Please select a day.</p>
              </div>

            </div>
          </div>
        </div>

        <div class="text-centered">

          <input
            class="button"
            type="submit"
            value="Confirm Appointment"
            :disabled="!selected_time"
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
        selected_day: null,
        selected_time: null,
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
        this.$root.initialAppointment.appointment_at = this.selectedAppointmentDate.utc().format('YYYY-MM-DD hh:mm:ss');
        this.$parent.appointmentDate = this.selectedAppointmentDate.format('YYYY-MM-DD hh:mm:ss a');
      },
      weekRange(date) {
        return moment(date).format('MMM. Do');
      },
      selectDay(dayObj) {
        this.selected_day = dayObj;
      },
      selectTime(index) {
        this.selected_time = this.selected_day.times[index].stored;
        this.$root.initialAppointment.appointment_at = this.selected_time;
      }
    },
    filters: {
      weekRange(date) {
        return moment(date).format('MMM. Do');
      },
      toLocal(date) {
        return moment.utc(date).local().format('h:mm a');
      }
    },
    computed: {
      week1() {
        if (this.availability.length) {
          return this.availability.slice(0, 5);
        }
      },
      week2() {
        if (this.availability.length) {
          return this.availability.slice(7, 12);
        }
      },
      week1Range() {
        if (this.availability.length) {
          return `${this.weekRange(this.week1[0].date)} - ${this.weekRange(this.week1[4].date)}`;
        }
      },
      week2Range() {
        if (this.availability.length) {
          return `${this.weekRange(this.week2[0].date)} - ${this.weekRange(this.week2[4].date)}`;
        }
      },
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
      if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
        this.$ma.trackEvent({
                fb_event: 'PageView',
                type: 'product',
                category: 'clicks',
                properties: { laravel_object: Laravel.user }
            });
        this.$ma.trackEvent({
            action: 'Date/Time',
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
