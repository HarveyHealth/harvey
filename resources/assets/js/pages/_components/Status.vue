<template>
  <div :class="classNames" v-show="isVisible">
    <label class="input__label" for="status">
      <div>status</div>
    </label>
    <span v-if="isEditable" class="custom-select">
      <select>
        <option v-for="status in statuses" :selected="status === appointmentstatus">{{ status }}</option>
      </select>
    </span>
    <span v-else class="input__item">{{ displayStatus }}</span>
  </div>
</template>

<script>
export default {
  props: ['appointmentstatus', 'classes', 'past', 'type', 'usertype'],
  data() {
    return {
      classNames: { 'input__container': true },
      initialStatus: 'Pending',
      statuses: ['Pending', 'No-Show-Patient', 'No-Show-Doctor',
                 'General Conflict', 'Canceled', 'Complete']
    }
  },
  computed: {
    displayStatus() {
      return this.past ? this.appointmentstatus : 'Pending';
    },
    // Status can only be modified by practitioners and admins and only during appointment update
    // All new appointments automatically have a status of 'pending' so it is not visible on new appointment flyout
    isEditable() {
      return this.usertype !== 'patient' && !this.past;
    },
    isVisible() {
      return this.type === 'update';
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
}
</script>
