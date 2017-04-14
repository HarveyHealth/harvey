<template>
  <ul class="calendar-week-container_days-wrapper">
    <li v-for="date in dates" class="calendar-item" :class="[{'selected' : selectedDate.day() === date.day()}]">
      <button class="calendar-item_link" @click.prevent="onDateChange(date)">
        <span>{{ date | datetime('dd') }}</span>
        <!-- {{isSameDate(selectedDate, date)}} -->
        <!-- <span>{{ date | datetime('DD') }}</span> -->
      </button>
    </li>
    <!-- <li class="calendar-item selected"><button class="calendar-item_link">Sun</button></li> -->
  </ul>
</template>

<script>
  import moment from 'moment';

  export default {
    props: ['selectedDate', 'maximumDays', 'startDateTime', 'dayOffset'],
    name: 'DatePicker',
    methods: {
      onDateChange(date) {
        const dateValue = moment(date).utc();
        this.$eventHub.$emit('datetime-change', {type: 'date', value: dateValue});
      }
    },
    computed: {
      dates() {
        const dates = [];
        let currentDate = moment(this.startDateTime).local();
        for (var i = 1; i <= this.maximumDays; i++) {
          dates.push(currentDate);
          currentDate = moment(this.startDateTime).local().add(i, 'days');
        }

        return dates;
      }
    },
    mounted() {
      //console.log(this.startDateTime.date());
      let localOffset = this.dayOffset || 0;
      console.log('local offset', localOffset);
      console.log(this.startDateTime.add(localOffset, 'days'))
      this.onDateChange(this.dates[0]);
    }
  }
</script>
