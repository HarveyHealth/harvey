<template>
  <div class="input__container">
    <span v-if="isLoading"></span>
    <SelectOptions2 v-else-if="editable && list.length"
      :detached-label="time ? null : 'Select time'"
      :is-disabled="!times.length"
      :is-required="false"
      :options="times"
      :selected="time"
      :on-select="handleSelect"
    />
    <span v-else-if="!editable" class="input__item">{{ currentTime | timeFilter }}</span>
  </div>
</template>

<script>
// components
import SelectOptions2 from '../../../commons/SelectOptions2.vue';
// other
import moment from 'moment';
import toLocal from '../../../utils/methods/toLocal';

export default {
  props: {
    currentTime: String,
    editable: Boolean,
    isLoading: Boolean,
    list: Array,
    setTime: Function,
    time: String,
  },
  components: {
    SelectOptions2
  },
  computed: {
    times() {
      if (this.list.length) {
        return this.list.map(timeObj => {
          return {
            value: toLocal(timeObj.stored, 'h:mm a'),
            data: timeObj
          }
        });
      } else {
        return [];
      }
    }
  },
  filters: {
    timeFilter(date) {
      return toLocal(date, 'h:mm a');
    }
  },
  methods: {
    handleSelect(e) {
      const timeObj = e.target.value
        ? this.times[e.target.selectedIndex - 1].data
        : null;
      this.setTime(timeObj);
    }
  }
}
</script>
