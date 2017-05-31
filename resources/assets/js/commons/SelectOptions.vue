<template>
  <div>
    <span v-if="loading">{{ loadingmsg }}</span>
    <span v-else class="custom-select" :data-default-option="defaultOption">
      <select v-model="selection" @change="selected($event.target)">
        <option v-for="item in options">{{ item.value }}</option>
      </select>
    </span>
  </div>
</template>

<script>
export default {
  props: ['emptylabel', 'defaultoption', 'firstempty', 'loadingmsg', 'options', 'selectevent'],
  data() {
    return {
      selection: ''
    }
  },
  computed: {
    defaultOption() {
      this.selection = this.defaultoption;
      return this.defaultoption || '';
    },
    loading() {
      return !this.options.length;
    }
  },
  methods: {
    selected(target) {
      const index = target.selectedIndex;
      this.$eventHub.$emit(this.selectevent, this.options[index])
    }
  }
}
</script>
