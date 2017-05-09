<template>
  <div :class="classNames">
    <label class="input__label" for="patient-name">client</label>
    <input class="input--text"
           type="text"
           name="patient-name"
           @input="value => { value = value; patient_name = value }"
           :value="value"
           :readonly="isReadonly" />
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: ['classes', 'patientname', 'type', 'usertype', 'value'],
  data() {
    return {
      classNames: { 'input__container': true },
      patient_name: this.patientname,
      isReadonly: !(this['user-type'] === 'admin' && this.type === 'new')
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  }
}
</script>
