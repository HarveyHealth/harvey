<template>
  <div v-if="visible" class="input__container">
    <label class="input__label">status</label>
    <SelectOptions v-if="editable"
      :on-select="handleSelect"
      :options="list"
      :selected="convertedStatus"
    />
    <span v-else class="input__item">{{ convertedStatus }}</span>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';
import convertStatus from '../utils/convertStatus';

export default {
  props: {
    editable: Boolean,
    list: Array,
    setStatus: Function,
    status: String,
    visible: Boolean,
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
    handleSelect(e) {
      this.setStatus(this.list[e.target.selectedIndex]);
    }
  }
}
</script>
