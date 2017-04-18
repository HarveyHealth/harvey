<template>
  <div class="calendar-week-container">
    <ul class="calendar-week-container_days-wrapper">
      <li v-for="time in timesList" class="calendar-item bar"
        :class="[{'selected' : hasBeenTouched && (selectedTime.hour() === time.hour()) && (selectedTime.minute() === time.minute())}]"
      >
        <button
          class="calendar-item_link"
          @click.prevent="onTimeChange(time)"
        >
          {{time | datetime('h:mm a')}}
        </button>
      </li>
    </ul>

    <p class="text-centered" v-if="!dateSelected">Please select a day.</p>
    <p class="text-centered" v-else-if="timesList.length < 1">There are no available times for this day.</p>
  </div>
</template>

<script>
  import moment from 'moment';

  export default {
    name: 'TimePicker',
    props: [
      'availability',
      'duration',
      'endOfDayHour',
      'minimumNotice',
      'now',
      'selectedDate',
      'selectedTime',
      'startDateTime',
      'startOfDayHour',
    ],
    data() {
      return {
        hasBeenTouched: false,
        dateSelected: false,
        startOfWeek: 1, // iso Monday
        timesList: [],
      }
    },
    methods: {
      setsTimeObject(hour, hourOffset = 0) {
        return moment({hour: hour + hourOffset, minute: 0});
      },

      onTimeChange(_time) {
        this.hasBeenTouched = true;
        if (_time >= this.startTime) {
          this.$eventHub.$emit('datetime-change', {type: 'time', value: _time});
        }
      },

      getTimes(_forDay = this.startOfWeek) {

        const times = [];
        let availableTime = {};

        this.availability[1].map(datetime => {
          availableTime = moment(datetime.time, 'HH:mm').utc();
          const selectedISOWeekday = moment(datetime.day, 'dddd').isoWeekday();

          if (selectedISOWeekday === _forDay) {
            times.push(availableTime);
          }
        });

        // update the local state
        this.timesList = times;

        return times;
      },

      // event handler for parent date change
      onDateChange(_obj) {

        // a date has been selected
        this.dateSelected = true;

        if (_obj.type === 'date') {
          // regenerate times
          this.getTimes(_obj.value.isoWeekday());
        }
      }
    },
    mounted() {
      this.$eventHub.$on('datetime-change', this.onDateChange);
    },
    computed: {
      startTime() {
        if (this.selectedDate > this.startDateTime) {
          return this.startOfDayHour;
        } else {
          return moment(this.startDateTime).local().hour();
        }
      }
    },
  }
</script>
