<template>
  <div :class="classNames">
    <label class="input__label" for="patient-name">client</label>
    <input v-if="type === 'new'"
           class="input--text"
           type="text"
           name="patient-name"
           v-model="patient_name" />
    <span v-else class="input__item">{{ patientName }}</span>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: ['classes', 'patientname', 'type', 'usertype', 'value'],
  data() {
    return {
      classNames: { 'input__container': true },
      patient_name: this.patientname || '',
      isEditable: !(this.usertype === 'admin' && this.type === 'new')
    }
  },
  computed: {
    patientName() {
      return this.patientname;
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  }
}
</script>
