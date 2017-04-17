<template>
  <div class="calendar-week-container">

    <div class="calendar-week-container_title-wrapper">
      <h3 class="calendar-week-container_title">This week</h3>
      <span class="calendar-week-container_date">
        {{getWeekWithOffset().start}} - {{getWeekWithOffset().end}}</span>
    </div>

    <ul class="calendar-week-container_days-wrapper">
      <li v-for="date in dates" class="calendar-item" :class="[{'selected' : selectedDate.day() === date.day()}]">
        <button class="calendar-item_link" @click.prevent="onDateChange(date)">
          <span>{{ date | datetime('dd') }}</span>
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
  import moment from 'moment';

  export default {
    props: ['selectedDate', 'maximumDays', 'startDateTime', 'availability'],
    name: 'DatePicker',
    methods: {
      onDateChange(date) {
        const dateValue = moment(date).utc();
        this.$eventHub.$emit('datetime-change', {type: 'date', value: dateValue});
      },

      getWeekWithOffset(_offset = 0) {
        const weekObject = {
          start: moment().add(_offset, 'days').startOf('isoweek').format('MMMM D'),
          end: moment().add(_offset, 'days').endOf('isoweek').format('MMMM D'),
        };

        return weekObject;
      },

      isDateAvailable(_date) {
        const testDate = _date;
        const testDayString = testDate.format('dddd');

        // let's assume it's not available from the start
        testDate.available = false;
        let availableDay = '';

        // compare day-of-the-week strings and test
        this.formattedDates.map(dateObject => {
          availableDay = dateObject.day;
          if (availableDay.includes(testDayString)) {
            testDate.available = true;
          }
        });

        return testDate;
      }
    },
    computed: {
      dates() {
        const dates = [];
        let currentDate = moment(this.startDateTime).local();
        for (var i = 1; i <= this.maximumDays; i++) {

          // test availability
          this.isDateAvailable(currentDate);

          dates.push(currentDate);
          currentDate = moment(this.startDateTime).local().add(i, 'days');
        }

        console.log('final dates', dates);

        return dates;
      },

      formattedDates() {
        // right now, this is getting two arrays, so we need to hard-code the index
        return this.availability[1].map((item) => {
          // this now a simple object of day:/time: keys
          // we can compare this with the generated moment object above
          // item.day === date.day("Tuesday")
          return item;
        });
      }
    },
    mounted() {
      this.onDateChange(this.dates[0]);
    }
  }
</script>
