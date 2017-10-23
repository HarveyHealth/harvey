<template>
  <div v-if="isVisible" class="input__container" :style="{ 'margin-bottom': editable ? '0.5em' : false }">
    <label class="input__label">duration</label>
    <SelectOptions v-if="editable"
      :attached-label="'Select duration'"
      :on-select="handleSelect"
      :options="list"
      :selected="duration"
    />
    <span v-else class="input__item">{{ duration }}</span>
    <span class="error-text" v-show="editable && !duration.length">Duration is required</span>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';

export default {
  props: {
    // Status string to be converted for SelectOptions
    duration: String,
    // Is the status editable or display only?
    editable: Boolean,
    // List of status objects (see SelectOptions for structure)
    list: Array,
    // What happens when a user selects a status?
    setDuration: Function,
    // Should we even display appointment status?
    isVisible: Boolean
  },
  components: {
    SelectOptions
  },
  methods: {
    handleSelect(e) {
      this.setDuration(this.list[e.target.selectedIndex - 1]);
    }
  }
};
</script>
