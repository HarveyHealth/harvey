<template>
  <ul class="calendar-week-container_days-wrapper">
    <li v-for="date in dates" class="calendar-item">
      <button class="calendar-item_link"></button>
    </li>
    <!-- <li class="calendar-item"><button class="calendar-item_link">Mon</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Tue</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Wed</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Thu</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Fri</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Sat</button></li>
    <li class="calendar-item selected"><button class="calendar-item_link">Sun</button></li> -->
  </ul>
</template>

<script>
  import moment from 'moment';

  export default {
    props: ['selectedDate', 'maximumDays', 'startDateTime'],
    name: 'DatePicker',
    methods: {

    },
    computed: {
      dates() {
        const dates = new Array();
        let currentDate = moment(this.startDateTime).local();

        for (var i = 0; i < this.maximumDays; i++) {
          dates.push(currentDate);
          currentDate = moment(this.startDateTime).local().add(i, 'days');
        }

        return dates;
      }
    }
  }
</script>
