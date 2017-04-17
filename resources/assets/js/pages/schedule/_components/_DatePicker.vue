<template>
  <div class="calendar-week-container">

    <div class="calendar-week-container_title-wrapper">
      <h3 class="calendar-week-container_title">This week</h3>
      <span class="calendar-week-container_date">
        {{getWeekWithOffset(weekOffset).start}} - {{getWeekWithOffset(weekOffset).end}}</span>
    </div>

    <ul class="calendar-week-container_days-wrapper">
      <li v-for="date in localDates" class="calendar-item" :class="[{'selected' : selectedDate.date() === date.date()}]">
        <button
          class="calendar-item_link"
          @click.prevent="onDateChange(date)"
          :value="date"
          :disabled="!date.available"
        >
          <span>{{ date | datetime('dd') }}</span>
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
  import moment from 'moment';

  export default {
    props: ['selectedDate', 'maximumDays', 'startDateTime', 'availability', 'weekOffset'],
    name: 'DatePicker',
    data() {
      return {
        localDates: [],
      }
    },
    methods: {
      onDateChange(date) {
        const dateValue = moment(date).utc();
        this.$eventHub.$emit('datetime-change', {type: 'date', value: dateValue});
      },

      getDates() {
        const dates = [];
        // let currentDate = moment(this.startDateTime).local();
        let currentDate = {};

        for (var i = 0; i < this.maximumDays; i++) {

          currentDate = moment(this.startDateTime).local().startOf('isoweek').add(i + this.weekOffset, 'days');

          // test availability
          this.isDateAvailable(currentDate);

          dates.push(currentDate);
        }

        return dates;
      },

      getWeekWithOffset(_offset = 0) {
        let weekStart = moment().add(_offset, 'days').startOf('isoweek');

        const weekObject = {
          start: weekStart.format('MMMM D'),
          end: weekStart.add(4, 'days').format('MMMM D'),
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
    created() {
      this.localDates = this.getDates();
    }
  }
</script>
