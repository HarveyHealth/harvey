<template>
  <div class="input__container" v-if="visible">
    <label class="input__label first">client</label>
    <SelectOptions v-if="editable"
      :attached-label="'Select patient'"
      :is-loading="$root.$data.global.loadingPatients"
      :loading-msg="'Loading patients...'"
      :on-select="handleSelect"
      :options="list"
      :selected="name"
    />
    <span v-else class="input__item patient-display">{{ name }}</span>
    <div class="font-sm"><a :href="'mailto:' + email">{{ email }}</a></div>
    <div class="font-sm"><a :href="'tel:' + phone" v-on:click="trackPhoneCall">{{ phone | phone }}</a></div>
    <p v-if="editable && address" v-html="address"></p>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';
import { phone } from '../../../utils/filters/textformat';

export default {
  props: {
    address: String,
    // Are we displaying the given name, or allowing user to select one?
    editable: Boolean,
    // If a name is given from a selected row
    name: String,
    // Associated email with name
    email: String,
    // The list of patients (see SelectOptions for structure)
    list: Array,
    // Associated phone with name
    phone: String,
    // What happens when a patient option is selected.
    // Function takes the selected patient data object
    setPatient: Function,
    // If we should display a patient name or options at all
    visible: Boolean
  },
  components: {
    SelectOptions
  },
  methods: {
    handleSelect(e) {
      this.setPatient(this.list[e.target.selectedIndex - 1].data);
    },
    trackPhoneCall() {
      if(this.$root.shouldTrack()) {
        // add "Click Phone Number" tracking here
      }
    }
  },
  filters: {
    phone
  }
}
</script>
