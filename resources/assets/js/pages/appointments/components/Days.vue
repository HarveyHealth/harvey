<template>
  <div class="input__container" style="margin-bottom: 0.5em;">
    <label class="input__label">{{ $$label }}</label>
    <span v-if="isLoading">Loading availability...</span>
    <SelectOptions v-else-if="editable && !noAvailability"
      :detached-label="day ? null : 'Select day'"
      :is-disabled="!list.length"
      :is-required="false"
      :on-select="handleSelect"
      :options="list"
      :selected="day"
    />
    <span v-else-if="!editable" class="input__item">{{ $$selectedDay }}</span>
    <span v-else class="input--warning">No available openings</span>

  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';
import moment from 'moment';
import toLocal from '../../../utils/methods/toLocal';

export default {
  props: {
    // A given day string to set as the selection or display
    day: String,
    // Do we need to give options, or just display given day?
    editable: Boolean,
    // Is the day data still loading?
    isLoading: Boolean,
    // List of day objects (see SelectOptions for structure)
    list: Array,
    // Is this for a new appointment or for updating an appointment
    mode: String,
    // Is the loaded data empty?
    noAvailability: Boolean,
    // What happens when a user selects a day
    setTimes: Function,
    // The UTC formatted date string for a given selection
    time: String
  },
  components: {
    SelectOptions
  },
  computed: {
    $$label() {
      if (this.editable && this.mode === 'new') return 'available times';
      if (this.editable && this.mode === 'update') return 'reschedule';
      return 'booked for';
    },
    $$selectedDay() {
      return this.time ? toLocal(this.time, 'dddd, MMMM Do') : '';
    }
  },
  methods: {
    handleSelect(e) {
      this.setTimes(e.target.value, e.target.selectedIndex);
    }
  }
}
</script>
