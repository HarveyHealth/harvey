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
    day: String,
    editable: Boolean,
    isLoading: Boolean,
    list: Array,
    mode: String,
    noAvailability: Boolean,
    setTimes: Function,
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
