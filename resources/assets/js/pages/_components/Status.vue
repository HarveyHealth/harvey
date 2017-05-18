<template>
  <div :class="classNames" v-show="isVisible">
    <label class="input__label" for="status">
      <div>status</div>
    </label>
    <span v-if="isEditable" class="custom-select">
      <select v-model="selected" @change="updateStatus()">
        <option v-for="(display, status) in statuses">{{ display }}</option>
      </select>
    </span>
    <span v-else class="input__item">{{ selected }}</span>
  </div>
</template>

<script>
export default {
  props: ['classes', 'past', 'statuses', 'type', 'usertype'],
  data() {
    return {
      classNames: { 'input__container': true },
      selected: '',
    }
  },
  computed: {
    // Status can only be modified by practitioners and admins and only during appointment update
    // All new appointments automatically have a status of 'pending' so it is not visible on new appointment flyout
    isEditable() {
      return this.usertype !== 'patient' && !this.past;
    },
    isVisible() {
      return this.type === 'update';
    }
  },
  methods: {
    updateStatus() {
      this.$eventHub.$emit('updateStatus', this.selected);
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
  mounted() {
    this.$eventHub.$on('setStatus', value => this.selected = this.statuses[value]);
  }
}
</script>
