<template>
  <div class="calendar-week-container">
    <ul class="calendar-week-container_days-wrapper">
      <li class="calendar-item"><button class="calendar-item_link">9a</button></li>
      <li class="calendar-item"><button class="calendar-item_link">10a</button></li>
      <li class="calendar-item"><button class="calendar-item_link">11a</button></li>
      <li class="calendar-item"><button class="calendar-item_link">12p</button></li>
      <li class="calendar-item"><button class="calendar-item_link">1p</button></li>
      <li class="calendar-item"><button class="calendar-item_link">2p</button></li>
      <li class="calendar-item selected"><button class="calendar-item_link">3p</button></li>
    </ul>
  </div>
</template>

<script>
  import moment from 'moment';

  export default {
    name: 'TimePicker',
    props: ['selectedDate', 'selectedTime', 'now', 'startOfDayHour', 'endOfDayHour', 'minimumNotice', 'duration', 'startDateTime'],
    methods: {
      range() {
        if (stop == null) {
          stop = start || 0
          start = 0
        }
        step = step || 1;

        const length = Math.max(Math.ceil((stop - start) / step), 0);
        let range = [];
        let index = 0;

        for (index; index < length; index++, start += step) {
          range[index] = start;
        }

        return range;
      },

      setsTimeObject(hour, hourOffset = 0) {
        return moment({hour: hour + hourOffset, minute: 0});
      },

      onTimeChange(time) {
        if (time >= this.startTime) {
          this.$eventHub.$emit('datetime-change', {type: 'time', value: time});
        }
      }
    },
    computed: {
      times() {
        return this.range(this.startOfDayHour, this.endOfDayHour, this.duration);
      },
      startTime() {
        if (this.selectedDate > this.startDateTime) {
          return this.startOfDayHour;
        } else {
          return moment(this.startDateTime).local().hour();
        }
      }
    },
    mounted() {
      this.onTimeChange(this.startTime);
    }
  }
</script>

<style>

</style>
