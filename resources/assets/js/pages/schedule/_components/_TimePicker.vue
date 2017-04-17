<template>
  <div class="calendar-week-container">
    <ul class="calendar-week-container_days-wrapper">
      <li v-for="time in times" class="calendar-item" :class="[{'selected' : hasBeenTouched && selectedTime.hour() === time.hour()}]">
        <button
          class="calendar-item_link"
          @click.prevent="onTimeChange(time)"
          :disabled="time.hour() < startTime"
        >
          {{time | datetime('h:mma')}}
        </button>
      </li>
    </ul>
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
      }
    },
    methods: {
      setsTimeObject(hour, hourOffset = 0) {
        return moment({hour: hour + hourOffset, minute: 0});
      },

      onTimeChange(time) {
        this.hasBeenTouched = true;
        if (time >= this.startTime) {
          this.$eventHub.$emit('datetime-change', {type: 'time', value: time});
        }
      },
    },
    computed: {
      times() {
        const times = [];
        let availableTime = {};

        this.availability[1].map(datetime => {
          availableTime = moment(datetime.time, 'HH:mm');
          times.push(availableTime);
        });

        return times;
      },
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
