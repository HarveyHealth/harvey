<template>
  <div :class="classNames" v-if="usertype !== 'patient'">
    <label class="input__label" for="patient-name">client</label>
    <span v-if="type === 'new'" class="custom-select" name="patient-name">
      <select @change="setPatientInfo($event.target.selectedIndex)" id="patient-selection">
        <option v-for="patient in patientlist">{{ patient.name }}</option>
      </select>
    </span>
    <span v-else class="input__item patient-display">{{ patientname || patient_name }}</span>
    <template v-if="type === 'update'">
      <div><a :href="'mailto:' + patientemail">{{ patientemail }}</a></div>
      <div><a :href="'tel:' + patientphone">{{ patientphone | formatPhone }}</a></div>
    </template>
    <template v-else-if="type === 'new'">
      <div><a :href="'mailto:' + patient_email">{{ patient_email }}</a></div>
      <div><a :href="'tel:' + patient_phone">{{ patient_phone | formatPhone }}</a></div>
    </template>

  </div>
</template>

<script>
import { phone } from '../../filters/textformat.js';
export default {
  props: ['classes', 'patientlist', 'patientemail', 'patientname', 'patientphone', 'type', 'usertype', 'value'],
  data() {
    return {
      classNames: { 'input__container': true },
      patient_name: '',
      patient_email: '',
      patient_phone: '',
      isEditable: !(this.usertype === 'admin' && this.type === 'new')
    }
  },
  filters: {
    formatPhone(num) {
      return phone(num);
    }
  },
  computed: {
    patientName() {
      return this.patientname;
    },
  },
  methods: {
    setPatientInfo(index) {
      this.patient_email = this.patientlist[index].email;
      this.patient_phone = this.patientlist[index].phone;
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
  mounted() {
    this.$eventHub.$on('setPatientInfo', () => {
      document.getElementById('patient-selection').value = this.patientlist[0].name;
      this.setPatientInfo(0);
    })
  }
}
</script>
