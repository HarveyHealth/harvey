<template>
  <div class="input__container" v-if="visible">
    <label class="input__label first">client</label>
    <SelectOptions v-if="editable"
      :attached-label="'Select Client'"
      :is-loading="$root.$data.global.loadingPatients"
      :loading-msg="'Loading clients...'"
      :on-select="handleSelect"
      :options="list"
      :selected="name"
    />
    <div v-if="name" class="margin-top" >
      <label class="input__label">Contact</label>
      <p class="font-sm"><a :href="'tel:' + phone" v-on:click="trackPhoneCall">{{ phone | phone }}</a></p>
      <p class="font-sm"><a :href="'mailto:' + email">{{ email }}</a></p>
    </div>
    <div v-if="address" class="margin-top">
      <label class="input__label">Location</label>
      <p v-html="address"></p>
    </div>
    <div v-if="name" class="margin-top">
      <label class="input__label">Payment</label>
      <p v-if="shouldShowPaymentError"><a href="/dashboard#/settings">Add Card</a></p>
      <p v-else class="copy-good">Card Found</p>
    </div>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';
import { phone } from '../../../utils/filters/textformat';

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
    // If we should display a patient name or options at all
    visible: Boolean
  },
  components: {
    SelectOptions
  },
  computed: {
    shouldShowPaymentError() {
      return this.context === 'new' && this.hasCard === false && Laravel.user.user_type !== 'admin';
    }
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
