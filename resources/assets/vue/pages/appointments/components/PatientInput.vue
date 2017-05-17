<template>
  <div :class="classNames" v-if="isVisible">
    <label class="input__label" for="patient_name">client</label>
    <span v-if="isEditable" class="custom-select">
      <select v-model="selected" @change="updatePatient($event)" name="patient_name">
        <option v-for="patient in patientlist" :data-id="patient.id">{{ patient.name }}</option>
      </select>
    </span>
    <span v-else class="input__item patient-display">{{ selected }}</span>
    <template v-if="isVisible">
      <div><a :href="'mailto:' + patientInfo[selectedId].email">{{ patientInfo[selectedId].email }}</a></div>
      <div><a :href="'tel:' + patientInfo[selectedId].phone">{{ patientInfo[selectedId].phone | formatPhone }}</a></div>
    </template>
  </div>
</template>

<script>
import { phone } from '../../../utils/filters/textformat.js';
export default {
  props: ['classes', 'patientlist', 'type', 'usertype'],
  data() {
    return {
      classNames: { 'input__container': true },
      selected: '',
      selectedId: '1',
    }
  },
  filters: {
    formatPhone(num) {
      return phone(num);
    }
  },
  computed: {
    isVisible() {
      return this.usertype !== 'patient';
    },
    isEditable() {
      return this.type === 'new';
    },
    patientInfo() {
      if (this.patientlist.length) {
        const info = {};
        this.patientlist.forEach(patient => {
          info[patient.id] = { name: patient.name, email: patient.email, phone: patient.phone }
        })
        return info;
      } else {
        return { '1': { name: '', email: '', phone: '' } }
      }

    }
  },
  methods: {
    updatePatient(e) {
      this.selectedId = e.target.children[e.target.selectedIndex].dataset.id;
      this.$eventHub.$emit('updatePatient', this.selectedId, this.selected);
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
  mounted() {
    this.$eventHub.$on('setPatient', id => {
      this.selectedId = id;
      this.selected = this.patientInfo[id].name;
    })
  },
  destroyed() {
    this.$eventHub.$off('setPatient');
  }
}
</script>
