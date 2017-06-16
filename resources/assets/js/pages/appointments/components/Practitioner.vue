<template>
  <div class="input__container" v-if="visible">
    <label class="input__label">doctor</label>
    <SelectOptions v-if="editable"
      :attached-label="'Select practitioner'"
      :is-loading="$root.$data.global.loadingPractitioners"
      :loading-msg="'Loading practitioners...'"
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
    // Is the component receiving a default practitioner name?
    name: String,
    // The list of practitioner objects (see SelectOptions for structure)
    list: Array,
    // What happens when a practitioner is selected
    setPractitioner: Function,
    // Do we even need to display a practitioner name or options?
    visible: Boolean
  },
  components: {
    SelectOptions
  },
  methods: {
    handleSelect(e) {
      // Subtract 1 from selectedIndex since there is an empty option in the list
      this.setPractitioner(this.list[e.target.selectedIndex - 1].data);
    }
  }
}
</script>
