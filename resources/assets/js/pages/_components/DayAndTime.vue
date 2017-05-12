<template>
  <div :class="classNames">
    <label class="input__label">
      <div v-if="past">booked for</div>
      <div v-else-if="type === 'update'">reschedule</div>
      <div v-else-if="type === 'new'">available times</div>
    </label>
    <span v-if="past" class="input__item">{{ conductedOn }}</span>
    <template v-else>
      <span class="custom-select">
        <select @change="selectedDay = $event.target.value">
          <option v-for="time, day in availableDays">{{ day }}</option>
        </select>
      </span>
      <span class="custom-select">
        <select>
          <option v-for="time in availableTimes">{{ time | normalTime }}</option>
        </select>
      </span>
    </template>
  </div>
</template>

<script>
import moment from 'moment-timezone';

export default {
  props: ['availability', 'classes', 'date', 'past', 'type'],
  data() {
    return {
      classNames: { 'input__container': true },
      currentWeeks: this.getCurrentWeeks(),
      times: [],
      selectedDay: '',
    }
  },
  filters: {
    normalTime(time) {
      return moment(time, 'HH:mm').format('h:mm a');
    }
  },
  computed: {
    availableDays() {
      return this.parseAvailability(this.availability);
    },
    availableTimes() {
      return this.availableDays[this.selectedDay];
    },
    conductedOn() {
      return this.past ? moment(this.date).format('dddd, MMMM Do [at] h:mm a') : false;
    }
  },
  methods: {
    getCurrentWeeks() {
      const weekStart = moment().startOf('week').add(1, 'days');
      let week1 = {};
      week1.Monday = weekStart.format('dddd, MMMM Do');
      week1.Tuesday = weekStart.add(1, 'days').format('dddd, MMMM Do');
      week1.Wednesday = weekStart.add(1, 'days').format('dddd, MMMM Do');
      week1.Thursday = weekStart.add(1, 'days').format('dddd, MMMM Do');
      week1.Friday = weekStart.add(1, 'days').format('dddd, MMMM Do');
      let week2 = {};
      week2.Monday = weekStart.add(3, 'days').format('dddd, MMMM Do');
      week2.Tuesday = weekStart.add(1, 'days').format('dddd, MMMM Do');
      week2.Wednesday = weekStart.add(1, 'days').format('dddd, MMMM Do');
      week2.Thursday = weekStart.add(1, 'days').format('dddd, MMMM Do');
      week2.Friday = weekStart.add(1, 'days').format('dddd, MMMM Do');

      return { week1: week1, week2: week2 };
    },
    transformTimeData(times, weeks) {
      const output = {};
      times
        .map(obj => obj.day)
        .filter((day, i, arr) => arr.indexOf(day) === i)
        .forEach(day => {
          output[day] = { display: weeks[day], time: [] };
        });
      return output;
    },
    parseAvailability(times) {
      if (!times.length) return [];
      const parsedTimes = {};
      const week1 = this.transformTimeData(times[0], this.currentWeeks.week1);
      const week2 = this.transformTimeData(times[1], this.currentWeeks.week2);
      times[0].forEach(obj => week1[obj.day].time.push(obj.time));
      times[1].forEach(obj => week2[obj.day].time.push(obj.time));
      for (let day in week1) {
        parsedTimes[week1[day].display] = week1[day].time;
      }
      for (let day in week2) {
        parsedTimes[week2[day].display] = week2[day].time;
      }

      this.selectedDay = Object.keys(parsedTimes)[0];
      return parsedTimes;
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
}
</script>
