<template>
  <div :class="classNames">
    <label class="input__label">
      <div v-if="past || displayOnly">booked for</div>
      <div v-else-if="type === 'update'">reschedule</div>
      <div v-else-if="type === 'new'">available times</div>
    </label>
    <span v-if="past || displayOnly" class="input__item">{{ conductedOn }}</span>
    <span v-else-if="loading">Loading availability...</span>
    <span v-else-if="noneAvailable" class="input--warning">Sorry, this doctor does not have any available appointment times.</span>
    <template v-else>
      <span :class="{ 'custom-select':true, 'show-day-label': !selectedDay }">
        <select v-model="selectedDay" @change="daySelect($event.target)">
          <option v-if="type === 'update'"></option>
          <option v-for="dayObj in _availability">{{ dayObj.date | formatDay }}</option>
        </select>
      </span>
      <span :class="{ 'custom-select':true, 'show-time-label': !selectedTime }">
        <select v-model="selectedTime" @change="timeSelect($event.target)">
          <option v-if="type === 'update'"></option>
          <option v-for="timeObj in times">{{ timeObj.local | formatTime }}</option>
        </select>
      </span>
    </template>
  </div>
</template>

<script>
import moment from 'moment-timezone';
import transformAvailability from '../../../utils/methods/transformAvailability';

export default {
  props: ['availability', 'classes', 'date', 'past', 'status', 'type'],
  data() {
    return {
      classNames: { 'input__container': true },
      dayIndex: 0,
      intialLoad: false,
      timeIndex: 0,
      loading: true,
      selectedDay: '',
      selectedTime: '',
    }
  },
  filters: {
    formatDay(date) {
      return moment(date).format('dddd, MMM Do');
    },
    formatTime(dateObj) {
      return dateObj.format('h:mm a');
    }
  },
  computed: {
    _availability() {
      let obj = transformAvailability(this.availability);
      if (obj) obj = obj.filter(day => day.times.length);

      if (this.type === 'update' && !this.initialLoad) {
        this.selectedDay = '';
        this.selectedTime = '';
      } else if (this.type === 'update') {
        this.selectedDay = moment(obj[this.dayIndex].date).format('dddd, MMM Do');
        this.selectedTime = moment.utc(obj[this.dayIndex].times[0].stored).local().format('h:mm a');
      }

      if (this.type === 'new' && !this.initialLoad) {
        this.selectedTime = moment.utc(obj[0].times[0].stored).local().format('h:mm a');
        this.selectedDay = moment(obj[this.dayIndex].date).format('dddd, MMM Do');
        this.send(moment(obj[this.dayIndex].times[0].stored).format('YYYY-MM-DD HH:mm:ss'));
      }

      this.initialLoad = true;

      return obj;
    },
    times() {
      if (this._availability.length && this.selectedDay) {
        if (this.dayIndex < 0) {
          return [];
        } else {
          return this._availability[this.dayIndex].times;
        }
      }
    },
    conductedOn() {
      return moment.utc(this.date).local().format('dddd, MMMM Do [at] h:mm a');
    },
    displayOnly() {
      return this.status !== 'pending' && Laravel.user.userType === 'patient';
    },
    noneAvailable() {
      return !this.availability.length;
    },
  },
  methods: {
    daySelect(target, index) {
      this.selectedDay = target.value;
      this.dayIndex = this.type === 'update' ? target.selectedIndex - 1 : target.selectedIndex;
      if (this.dayIndex >= 0) {
        this.selectedTime = moment.utc(this._availability[this.dayIndex].times[0].stored).local().format('h:mm a');
        this.send(moment.utc(this._availability[this.dayIndex].times[0].stored).format('YYYY-MM-DD HH:mm:ss'));
      } else {
        this.selectedTime = '';
        this.send(this.date);
      }
    },
    timeSelect(target) {
      const index = this.type === 'update' ? target.selectedIndex - 1 : target.selectedIndex;
      this.send(moment.utc(this._availability[this.dayIndex].times[index].stored).format('YYYY-MM-DD HH:mm:ss'));
    },
    send(dateTime) {
      this.$eventHub.$emit('updateDayTime', dateTime);
    },
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
  mounted() {
    this.$eventHub.$on('callFlyout', active => {
      this.loading = true;
      this.initialLoad = false;
      this.selectedDay = '';
      this.selectedTime = '';
      this.dayIndex = 0;
      this.timeIndex = 0;
    });
    this.$eventHub.$on('availabilityResponse', response => {
      if (response === 'refresh') {
        this.loading = true;
      } else {
        this.loading = false;
        this.noneAvailable = !response;
      }
    });
  },
  destroyed() {
    this.$eventHub.$off('callFlyout');
    this.$eventHub.$off('availabilityResponse');
  }
}
</script>
