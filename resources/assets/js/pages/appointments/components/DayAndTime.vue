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
      <span class="custom-select">
        <select v-model="testDay" @change="daySelect($event.target)">
          <option v-for="dayObj in _availability">{{ dayObj.date | formatDay }}</option>
        </select>
      </span>
      <span class="custom-select">
        <select @change="timeSelect($event.target)">
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
      loading: true,
      testDay: '',
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
      this.testDay = this.testDay
        ? moment(obj[this.dayIndex].date).format('dddd, MMM Do')
        : moment(obj[0].date).format('dddd, MMM Do');
      this.send(moment.utc(obj[this.dayIndex].times[0].stored).format('YYYY-MM-DD HH:mm:ss'));
      return obj;
    },
    times() {
      if (this._availability.length) {
        return this._availability[this.dayIndex].times;
      }
    },
    conductedOn() {
      return moment.utc(this.date).local().format('dddd, MMMM Do [at] h:mm a');
    },
    displayOnly() {
      return this.status !== 'pending' && Laravel.user.userType === 'patient';
    },
    noneAvailable() {
      return !Object.keys(this._availability).length;
    },
  },
  methods: {
    daySelect(target, index) {
      this.testDay = target.value;
      this.dayIndex = target.selectedIndex;
    },
    timeSelect(target) {
      this.send(moment.utc(this._availability[this.dayIndex].times[target.selectedIndex].stored).format('YYYY-MM-DD HH:mm:ss'));
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
    this.$eventHub.$on('callFlyout', active => this.loading = true);
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
