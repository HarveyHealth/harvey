<template>
  <div class="calendar-week-container">
    <ul class="calendar-week-container_days-wrapper">
      <li v-for="time in times" class="calendar-item" :class="[{'selected' : hasBeenTouched && selectedTime.hour() === time.hour()}]">
        <button
          class="calendar-item_link"
          @click.prevent="onTimeChange(time)"
          :disabled="time.hour() < startTime"
        >
          {{time | datetime('ha')}}
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
  import moment from 'moment';

  export default {
    name: 'TimePicker',
    props: ['selectedDate', 'selectedTime', 'now', 'startOfDayHour', 'endOfDayHour', 'minimumNotice', 'duration', 'startDateTime'],
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
      }
    },
    computed: {
      times() {
        const times = [];
        for (let i = this.startOfDayHour; i <= this.endOfDayHour; i++) {
          times.push(moment({hour: i, minute: 0}));
        }
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
