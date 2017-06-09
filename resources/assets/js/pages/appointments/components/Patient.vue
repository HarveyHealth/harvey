<template>
  <div class="input__container" v-if="visible">
    <label class="input__label">client</label>
    <SelectOptions v-if="editable"
      :attached-label="'Select patient'"
      :is-loading="$root.$data.global.loadingPatients"
      :loading-msg="'Loading patients...'"
      :on-select="handleSelect"
      :options="list"
      :selected="name"
    />
    <span v-else class="input__item patient-display">{{ name }}</span>
    <div><a :href="'mailto:' + email">{{ email }}</a></div>
    <div><a :href="'tel:' + phone">{{ phone | phone }}</a></div>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';
import { phone } from '../../../utils/filters/textformat';

export default {
  props: {
    editable: Boolean,
    name: String,
    email: String,
    list: Array,
    phone: String,
    setPatient: Function,
    visible: Boolean
  },
  components: {
    SelectOptions
  },
  methods: {
    handleSelect(e) {
      this.setPatient(this.list[e.target.selectedIndex - 1].data);
    }
  },
  filters: {
    phone
  }
}
</script>
