<template>
  <div :class="classNames">
    <label class="input__label" for="patient-name">client</label>
    <input class="input--text"
           type="text"
           name="patient-name"
           v-model="patientName"
           :readonly="isReadonly" />
  </div>
</template>

<script>
export default {
  props: ['classes', 'patientname', 'readonly'],
  data() {
    return {
      classNames: {
        'input__container': true
      },
      patientName: this.patientname || '',
      isReadonly: this.readonly || false
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  }
}
</script>
