<template>
  <div class="input__container" v-if="visible">
    <label class="input__label">client</label>
    <SelectOptions
      v-if="editable"
      :defaultoption="firstName"
      :loadingmsg="'Loading patients...'"
      :options="list"
      :selectevent="'selectPatient'"
    />
    <span v-else class="input__item patient-display">{{ name }}</span>
    <div><a :href="'mailto:' + this.email">{{ this.email }}</a></div>
    <div><a :href="'tel:' + this.phone">{{ this.phone }}</a></div>
  </div>
</template>

<script>
// components
import SelectOptions from '../../../commons/SelectOptions.vue';

export default {
  props: ['editable', 'name', 'email', 'list', 'phone', 'visible'],
  components: {
    SelectOptions
  },
  computed: {
    firstName() {
      return this.list.length ? this.list[0].value : '';
    }
  }
}
</script>
