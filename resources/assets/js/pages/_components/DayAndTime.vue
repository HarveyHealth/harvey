<template>
  <div :class="classNames">
    <label class="input__label">
      <div v-if="past">booked for</div>
      <div v-else-if="type === 'update'">reschedule</div>
      <div v-else-if="type === 'new'">available times</div>
    </label>
    <span v-if="past" class="input__item">{{ conductedOn }}</span>
    <span v-else-if="noneAvailable" class="input--warning">{{ warningMessage }}</span>
    <template v-else>
      <span class="custom-select">
        <select v-model="day" @change="selectDay($event.target)" name="appointment_day">
          <option v-if="type === 'update'"></option>
          <option v-for="t, d in availableDays">{{ d }}</option>
        </select>
      </span>
      <span class="custom-select">
        <select v-model="time" @change="selectTime($event.target)" name="appointment_time">
          <option v-if="type === 'update'"></option>
          <option v-for="t in availableTimes">{{ normalTime(t) }}</option>
        </select>
      </span>
    </template>
    {{ dateEventSend }}
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
      day: '',
      timeIndex: 0,
      time: '',
      warningMessage: 'No available slots'
    }
  },
  computed: {
    availableDays() {
      const days = this.parseAvailability(this.availability);
      this.day = this.type === 'update' ? '' : Object.keys(days)[0];
      return days;
    },
    availableTimes() {
      const times = this.availableDays[this.selectedDay];
      this.time = this.type === 'update' ? '' : this.normalTime(times[0]);
      this.timeIndex = 0;
      return times;
    },
    conductedOn() {
      return this.past ? moment(this.date).format('dddd, MMMM Do [at] h:mm a') : false;
    },
    noneAvailable() {
      return !Object.keys(this.availableDays).length;
    },
    // Using this computed property as an event emitter
    dateEventSend() {
      if (!this.noneAvailable && this.day && this.time) {
        Vue.nextTick(() => {
          this.$eventHub.$emit('updateDayTime', this.availableDays[this.day][this.timeIndex]);
        })
      }
    }
  },
  methods: {
    normalDay(day) {
      return moment(day).format('dddd, MMMM Do');
    },
    normalTime(time) {
      return moment(time, 'HH:mm').format('h:mm a');
    },
    selectDay(target) {
      this.selectedDay = target.value;
      this.timeIndex = 0;
      if (target.value === '') {
        this.$eventHub.$emit('updateDayTime', moment(this.date));
      }
    },
    selectTime(target) {
      this.timeIndex = this.type === 'update' ? target.selectedIndex - 1 : target.selectedIndex;
    },
    getCurrentWeeks() {
      const weekStart = moment().startOf('week').add(1, 'days');
      let week1 = {}, week2 = {};
      let week1b = {}, week2b = {};

      week1.Monday = weekStart.format('YYYY-MM-DD hh:mm:ss');
      week1.Tuesday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week1.Wednesday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week1.Thursday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week1.Friday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week1.Saturday = weekStart.add(1, 'days').format('YYYY-MM-DD');

      week2.Monday = weekStart.add(2, 'days').format('YYYY-MM-DD');
      week2.Tuesday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week2.Wednesday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week2.Thursday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week2.Friday = weekStart.add(1, 'days').format('YYYY-MM-DD');
      week2.Saturday = weekStart.add(1, 'days').format('YYYY-MM-DD');

      for (let day in week1) {
        week1b[day] = { raw: week1[day], formatted: moment(week1[day]).format('dddd, MMMM Do') }
      }
      for (let day in week2) {
        week2b[day] = { raw: week2[day], formatted: moment(week2[day]).format('dddd, MMMM Do') }
      }

      return { week1: week1b, week2: week2b };
    },
    transformTimeData(times, weeks) {
      const output = {};
      times
        .map(obj => obj.day)
        .filter((day, i, arr) => arr.indexOf(day) === i)
        .forEach(day => {
          output[day] = { formatted: { display: weeks[day].formatted, time: [] }, raw: weeks[day].raw };
        });
      return output;
    },
    parseAvailability(times) {
      if (!times.length) return [];

      const parsedTimes = {};
      const week1 = this.transformTimeData(times[0], this.currentWeeks.week1);
      const week2 = this.transformTimeData(times[1], this.currentWeeks.week2);

      times[0].forEach(obj => {
        week1[obj.day].formatted.time.push(moment(`${week1[obj.day].raw} ${obj.time}:00`))
      });
      times[1].forEach(obj => {
        week2[obj.day].formatted.time.push(moment(`${week2[obj.day].raw} ${obj.time}:00`))
      });

      for (let day in week1) {
        parsedTimes[week1[day].formatted.display] = week1[day].formatted.time;
      }
      for (let day in week2) {
        parsedTimes[week2[day].formatted.display] = week2[day].formatted.time;
      }

      this.selectedDay = Object.keys(parsedTimes)[0];
      return parsedTimes;
    },
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
}
</script>
