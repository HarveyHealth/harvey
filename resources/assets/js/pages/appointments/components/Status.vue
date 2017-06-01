<template>
  <div v-if="visible" class="input__container">
    <label class="input__label">status</label>
    <SelectOptions
      v-if="editable"
      :forceevent="'forceStatusSelect'"
      :options="list"
      :selectevent="'selectStatus'"
    />
    <span v-else class="input__item">{{ status | convertStatus }}</span>
  </div>
</template>

<script>
// components
import SelectOptions from '../../../commons/SelectOptions.vue';
// other
import convertStatus from '../convertStatus';

export default {
  props: ['editable', 'list', 'status', 'visible'],
  components: {
    SelectOptions
  },
  filters: {
    convertStatus
  }
}
</script>
