<template>
  <div class="input__container" v-if="isVisible">
    <label class="input__label first">client</label>

    <autocomplete v-if="editable"
        anchor="search_name"
        label=false
        url=true
        :debounce="500"
        :onShouldGetData="getData"
        :on-select="handlePatientSelect"
        :initValue="name"
    >
    </autocomplete>
    <p v-else-if="isVisible && name">{{ name }}</p>
    <div class="Flyout-SubSection" v-if="name">
      <div>
        <label class="font-xs font-uppercase font-normal copy-muted-2">Contact</label>
        <p class="font-sm"><a :href="'tel:' + phone">{{ phone | phone }}</a></p>
      </div>
      <div v-if="address" class="margin-top">
        <label class="font-xs font-uppercase font-normal copy-muted-2">Location</label>
        <p v-html="address"></p>
      </div>
      <div v-if="name" class="margin-top">
        <label class="font-xs font-uppercase font-normal copy-muted-2">Card</label>
        <p v-if="hasCard" class="copy-good">Card Found</p>
        <p v-else-if="userType !== 'patient'" class="copy-error">No card on file.</p>
        <p v-else><a href="/dashboard#/settings">Add Card</a></p>
      </div>
    </div>
  </div>
</template>

<script>

import { phone } from '../../../utils/filters/textformat';
import Autocomplete from '../../../commons/Autocomplete.vue';
require("../../../../css/vendors/vue2-autocomplete.css");

export default {
  props: {
    address: String,
    // flyout mode 'new' or 'edit'
    context: String,
    // Does the selected patient have a credit card confirmed?
    hasCard: Boolean,
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
    userType: String,
    // If we should display a patient name or options at all
    isVisible: Boolean
  },
  components: {
    Autocomplete
  },
  computed: {
    shouldShowPaymentError() {
      return this.context === 'new' && this.hasCard === false && Laravel.user.user_type !== 'admin';
    }
  },
  methods: {
      getData(value){
          return new Promise((resolve) => {
              if (value != ""){
                  this.$root.requestPatients(value,(patients)=>{
                      resolve(patients);
                  });
              }
              else{
                  resolve([]);
              }
          });
      },
      handlePatientSelect(obj) {
        this.setPatient(obj);
      },

      handleSelect(e) {
        this.setPatient(this.list[e.target.selectedIndex - 1].data);
      }
  },
  filters: {
    phone
  }
};
</script>
