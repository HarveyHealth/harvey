<template>
  <div class="input__container" v-if="list.length">
    <span v-if="isLoading"></span>
    <SelectOptions v-else-if="editable && list.length"
      :detached-label="time ? null : 'Select Time'"
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
import SelectOptions from '../../../commons/SelectOptions.vue';
// other
import moment from 'moment';
import toLocal from '../../../utils/methods/toLocal';

export default {
    props: {
    // UTC formatted string for displaying given time
        currentTime: String,
        // Should times be editabled or display only?
        editable: Boolean,
        // Is the times data still loading?
        isLoading: Boolean,
        // List of times objects (see SelectOptions for structure)
        list: Array,
        // What happens when a user selects a time?
        setTime: Function,
        // Hours and minutes string that corresponds to a given time option
        time: String
    },
    components: {
        SelectOptions
    },
    computed: {
    // Transforming times into structure appropriate for SelectOptions
        times() {
            if (this.list.length) {
                return this.list.map(timeObj => {
                    return {
                        value: this.$root.addTimezone(moment(timeObj.stored).format('h:mm a')),
                        data: timeObj
                    };
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
    // Check to see if the user clicked the empty option or not
        handleSelect(e) {
            const timeObj = e.target.value
                ? this.times[e.target.selectedIndex - 1].data
                : null;
            this.setTime(timeObj);
        }
    }
};
</script>
