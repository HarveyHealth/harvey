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
    editable: Boolean,
    name: String,
    list: Array,
    setPractitioner: Function,
    visible: Boolean
  },
  components: {
    SelectOptions
  },
  methods: {
    handleSelect(e) {
      this.setPractitioner(this.list[e.target.selectedIndex - 1].data);
    }
  }
}
</script>
