<template>
  <div class="input__container">
    <span v-if="isloading"></span>
    <SelectOptions
      v-else-if="editable"
      :emptylabel="'show-time-label'"
      :forceevent="'forceTimeSelect'"
      :isdisabled="!times.length"
      :options="times"
      :selectevent="'selectTime'"
    />
    <span v-else class="input__item">{{ time | timeFilter }}</span>
  </div>
</template>

<script>
// components
import SelectOptions from '../../../commons/SelectOptions.vue';
// other
import moment from 'moment';
import toLocal from '../../../utils/methods/toLocal';

export default {
  props: ['editable','isloading', 'list', 'time'],
  components: {
    SelectOptions
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
  }
}
</script>
