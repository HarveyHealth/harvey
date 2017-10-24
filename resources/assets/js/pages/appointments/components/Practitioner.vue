<template>
  <div class="input__container" v-if="isVisible">
    <label :class="{ 'input__label': true, 'first': isPatient }">Doctor</label>
    <SelectOptions v-if="editable"
      :attached-label="'Select Doctor'"
      :is-disabled="isDisabled"
      :is-loading="$root.$data.global.loadingPractitioners"
      :loading-msg="'Loading doctors...'"
      :on-select="handleSelect"
      :options="list"
      :selected="name"
    />
    <span v-else class="input__item">{{ name }}</span>
  </div>
</template>

<script>
import SelectOptions from '../../../commons/SelectOptions.vue';

export default {
  props: {
    // Is the practitioner list editable?
    editable: Boolean,
    // List should be visible, but disabled if the patient dropdown is editable and has not been selected yet
    isDisabled: Boolean,
    // Is the component receiving a default practitioner name?
    name: String,
    // The list of practitioner objects (see SelectOptions for structure)
    list: Array,
    // What happens when a practitioner is selected
    setPractitioner: Function,
    // Do we even need to display a practitioner name or options?
    isVisible: Boolean
  },
  components: {
    SelectOptions
  },
  computed: {
    isPatient() {
      return Laravel.user.user_type === 'patient';
    }
  },
  methods: {
    handleSelect(e) {
      // Subtract 1 from selectedIndex since there is an empty option in the list
      this.setPractitioner(this.list[e.target.selectedIndex - 1].data);
    }
  }
};
</script>
