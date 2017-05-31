<template>
  <div>
    <span v-if="loading">{{ loadingmsg }}</span>
    <span v-else class="custom-select" :class="labelOverlay" :data-default-option="defaultOption">
      <select v-model="selection" @change="selected($event.target)" :disabled="isdisabled">
        <option v-if="emptylabel !== ''"></option>
        <option v-for="item in options">{{ item.value }}</option>
      </select>
    </span>
  </div>
</template>

<script>
export default {
  props: [
    'defaultoption',
    'emptylabel',
    'firstempty',
    'forceevent',
    'isdisabled',
    'loadingmsg',
    'options',
    'selectevent'
  ],
  data() {
    return {
      selection: ''
    }
  },
  computed: {
    // Helps us setup an initial default value
    defaultOption() {
      this.selection = this.defaultoption;
      return this.defaultoption || '';
    },
    labelOverlay() {
      let classes = {};
      if (this.emptylabel && this.emptylabel !== '' && !this.selection) {
        classes[`${this.emptylabel}`] = true;
      }
      if (this.isdisabled) {
        classes['isdisabled'] = true;
      }
      return classes;
    },
    loading() {
      return !this.options.length && this.loadingmsg !== undefined;
    }
  },
  methods: {
    selected(target) {
      const index = this.emptylabel !== ''
        ? target.selectedIndex === 0 ? null : target.selectedIndex - 1
        : target.selectedIndex;
      this.$eventHub.$emit(this.selectevent, this.options[index])
    }
  },
  mounted() {
    if (this.forceevent) {
      this.$eventHub.$on(this.forceevent, forced => this.selection = forced);
    }
  },
  destroyed() {
    if (this.forceevent) {
      this.$eventHub.$off(this.forceevent);
    }
  }
}
</script>
