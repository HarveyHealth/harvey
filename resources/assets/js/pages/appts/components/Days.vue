<template>
  <div class="input__container">
    <label class="input__label" v-if="editable && mode === 'new'">available times</label>
    <label class="input__label" v-if="editable && mode === 'update'">reschedule</label>
    <label class="input__label" v-else>booked for</label>
    <SelectOptions
      v-if="editable"
      :emptylabel="'show-day-label'"
      :loadingmsg="'Loading availability...'"
      :options="days"
      :selectevent="'selectDay'"
    />
    <span v-else class="input__item patient-display">{{ time | dayFilter }}</span>
  </div>
</template>

<script>
// components
import SelectOptions from '../../../commons/SelectOptions.vue';

// other
import moment from 'moment';
import toLocal from '../../../utils/methods/toLocal';

export default {
  props: ['editable', 'list', 'mode', 'time'],
  components: {
    SelectOptions
  },
  computed: {
    days() {
      if (this.list.length) {
        return this.list
          .filter(obj => obj.times.length)
          .map(obj => {
            return { value: moment(obj.date).format('dddd, MMM Do'), data: obj };
          });
      } else {
        return [];
      }
    }
  },
  filters: {
    dayFilter(date) {
      return toLocal(date, 'dddd, MMMM Do');
    }
  }
}
</script>
