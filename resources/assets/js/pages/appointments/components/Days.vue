<template>
  <div class="input__container" v-if="isVisible">
    <label class="input__label">{{ $$label }}</label>
    <p class="copy-main-sm" v-if="isLoading">Loading availability...</p>
    <SelectOptions v-else-if="editable && !noAvailability"
      :detached-label="day ? null : 'Select Day'"
      :is-disabled="!list.length"
      :is-required="false"
      :on-select="handleSelect"
      :options="list"
      :selected="day"
    />
    <span v-else-if="!editable" class="input__item">{{ $$selectedDay }}</span>
    <span v-else class="input--warning">No dates available.</span>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';
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
    time: String,
    isVisible: Boolean
  },
  components: {
    SelectOptions
  },
  computed: {
    $$label() {
      if (this.editable && this.mode === 'new') return 'Date & Time';
      if (this.editable && this.mode === 'update') return 'Reschedule';
      return 'booked for';
    },
    components: {
        SelectOptions
    },
    computed: {
        $$label() {
            if (this.editable && this.mode === 'new') return 'Date & Time';
            if (this.editable && this.mode === 'update') return 'Reschedule';
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
};
</script>
