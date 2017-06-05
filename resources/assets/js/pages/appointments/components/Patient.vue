<template>
  <div class="input__container" v-if="visible">
    <label class="input__label">client</label>
    <!-- <SelectOptions
      v-if="editable"
      :defaultoption="firstName"
      :loadingmsg="'Loading patients...'"
      :options="list"
      :selectevent="'selectPatient'"
    /> -->
    <SelectOptions2
      v-if="editable"
      :attached-label="'Select patient'"
      :is-loading="$root.$data.global.loadingPatients"
      :loading-msg="'Loading patients...'"
      :on-select="handleSelect"
      :options="list"
      :selected="name"
    />
    <span v-else class="input__item patient-display">{{ name }}</span>
    <div><a :href="'mailto:' + this.email">{{ this.email }}</a></div>
    <div><a :href="'tel:' + this.phone">{{ this.phone | phone }}</a></div>
  </div>
</template>

<script>
// components
import SelectOptions from '../../../commons/SelectOptions.vue';
import SelectOptions2 from '../../../commons/SelectOptions2.vue';
//other
import { phone } from '../../../utils/filters/textformat';

export default {
  props: ['editable', 'loading', 'name', 'email', 'list', 'phone', 'setPatient', 'visible'],
  components: {
    SelectOptions,
    SelectOptions2
  },
  data() {
    return {
      selected: '',
      selectedIndex: null
    }
  },
  computed: {
    firstName() {
      return this.list.length ? this.list[0].value : '';
    }
  },
  methods: {
    handleSelect(e) {
      this.selected = e.target.value;
      this.setPatient(this.list[e.target.selectedIndex - 1].data);
    }
  },
  filters: {
    phone
  }
}
</script>
