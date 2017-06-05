<template>
  <div v-if="visible" class="input__container">
    <label class="input__label">status</label>
    <SelectOptions2 v-if="editable"
      :on-select="handleSelect"
      :options="list"
      :selected="convertedStatus"
    />
    <span v-else class="input__item">{{ convertedStatus }}</span>
  </div>
</template>

<script>
import SelectOptions2 from '../../../commons/SelectOptions2.vue';
import convertStatus from '../convertStatus';

export default {
  props: {
    editable: Boolean,
    list: Array,
    setStatus: Function,
    status: String,
    visible: Boolean,
  },
  components: {
    SelectOptions2
  },
  computed: {
    convertedStatus() {
      return convertStatus(this.status) || '';
    }
  },
  filters: {
    convertStatus
  },
  methods: {
    handleSelect(e) {
      this.setStatus(this.list[e.target.selectedIndex]);
    }
  }
}
</script>
