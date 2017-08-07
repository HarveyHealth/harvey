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

              <div class="calendar-week-container">
                <small>Timezone: {{$root.addTimezone()}}</small>
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

  export default {
    name: 'DateTime',
    props: ['availability'],
    data() {
      return {
        selected_day: null,
        selected_time: null,
        title: 'Choose date and time',
        subtitle: 'Lastly, tell us the best date and time you would like to schedule a 45-60 minute phone consultation with your chosen physician.',
        animClasses: {
          'anim-fade-slideup': true,
          'anim-fade-slideup-in': false,
        },
      }
    },
    methods: {
      firstStep() {
        this.$parent.selectedDate = this.selectedDate
        this.$parent.selectedTime = this.selectedTime
        this.$parent.previous();
        this.$parent.previous();
      },
      previousStep() {
        this.$parent.selectedDate = this.selectedDate
        this.$parent.selectedTime = this.selectedTime
        this.$parent.previous();
      },
      selectDay(dayObj) {
        this.selected_day = dayObj;
      },
      selectTime(index) {
        this.selected_time = this.selected_day.times[index].stored;
        this.$root.initialAppointment.appointment_at = this.selected_time;
      },
      weekRange(date) {
        return moment(date).format('MMM. Do');
      },
    },
    filters: {
      toLocal(date) {
        return moment.utc(date).local().format('h:mm a');
      },
      weekRange(date) {
        return moment(date).format('MMM. Do');
      },
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
    },
    mounted() {
      this.$eventHub.$on('datetime-change', this.onDateTimeChange);
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300)
    },
  }
</script>
