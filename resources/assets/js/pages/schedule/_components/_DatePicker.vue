<template>
  <ul class="calendar-week-container_days-wrapper">
    <li v-for="date in dates" class="calendar-item" :class="[{'selected' : isSameDate(selectedDate, date)}]">
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
    props: ['selectedDate', 'maximumDays', 'startDateTime'],
    name: 'DatePicker',
    methods: {
      onDateChange(date) {
        const dateValue = moment(date).utc().format("YYYY-MM-DD HH:mm:ss");
        this.$eventHub.$emit('datetime-change', {type: 'date', value: dateValue});
      },

      isSameDate(a, b) {
          return a == b || (moment(a).isValid() && moment(a).diff(b) == 0);
      }
    },
    computed: {
      dates() {
        const dates = new Array();
        let currentDate = moment(this.startDateTime).local();

        console.log("current date", currentDate);

        for (var i = 1; i <= this.maximumDays; i++) {
          dates.push(currentDate);
          currentDate = moment(this.startDateTime).local().add(i, 'days');
        }

        return dates;
      }
    },
    mounted() {
      this.onDateChange(this.dates[0]);
    }
  }
</script>
