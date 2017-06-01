<template>
  <div class="input__container" v-if="visible">
    <label class="input__label">doctor</label>
    <SelectOptions
      v-if="editable"
      :defaultoption="firstName"
      :loadingmsg="'Loading practitioners...'"
      :options="list"
      :selectevent="'selectPractitioner'"
    />
    <span v-else class="input__item">{{ name }}</span>
  </div>
</template>

<script>
// components
import SelectOptions from '../../../commons/SelectOptions.vue';

export default {
  props: ['editable', 'name', 'list', 'visible'],
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
