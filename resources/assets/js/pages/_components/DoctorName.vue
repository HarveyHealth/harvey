<template>
  <div :class="classNames">
    <label class="input__label" for="doctor-name">
      <div>doctor</div>
    </label>
    <span v-if="isEditable" class="custom-select">
      <select>
        <option
          v-for="doc in doctorlist"
          @click="chosenDoctor = doc"
          :selected="doc === doctorname" >{{ doc }}</option>
      </select>
    </span>
    <span v-else class="input__item">{{ chosenDoctor }}</span>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: ['classes', 'doctorlist', 'doctorname', 'type', 'usertype'],
  data() {
    return {
      classNames: { 'input__container': true },
    }
  },
  computed: {
    chosenDoctor() {
      return this.doctorname;
    },
    isEditable() {
      return ( this.usertype === 'admin' || this.usertype === 'patient' ) && this.type === 'new';
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  }
}
</script>
