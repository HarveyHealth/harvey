<template>
  <div class="input__container" v-if="visible">
    <label class="input__label">doctor</label>
    <SelectOptions2 v-if="editable"
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
import SelectOptions2 from '../../../commons/SelectOptions2.vue';

export default {
  props: {
    editable: Boolean,
    name: String,
    list: Array,
    setPractitioner: Function,
    visible: Boolean
  },
  components: {
    SelectOptions2
  },
  methods: {
    handleSelect(e) {
      this.setPractitioner(this.list[e.target.selectedIndex - 1].data);
    }
  }
}
</script>
