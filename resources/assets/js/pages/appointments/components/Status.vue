<template>
  <div v-if="isVisible" class="input__container">
    <label class="input__label">status</label>
    <SelectOptions v-if="editable"
      :on-select="handleSelect"
      :options="list"
      :selected="convertedStatus"
    />
    <span v-else class="input__item">{{ displayStatus(convertedStatus, Config.user.info.user_type) }}</span>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';
import convertStatus from '../utils/convertStatus';
import displayStatus from '../utils/displayStatus';

export default {
  props: {
    // Is the status editable or display only?
    editable: Boolean,
    // List of status objects (see SelectOptions for structure)
    list: Array,
    // What happens when a user selects a status?
    setStatus: Function,
    // Status string to be converted for SelectOptions
    status: String,
    // Should we even display appointment status?
    isVisible: Boolean,
  },
  components: {
    SelectOptions
  },
  computed: {
    convertedStatus() {
      return convertStatus(this.status) || '';
    }
  },
  methods: {
    displayStatus,
    handleSelect(e) {
      this.setStatus(this.list[e.target.selectedIndex]);
    }
  }
}
</script>
