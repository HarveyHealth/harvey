<template>
  <div class="main-container">

    <div class="nav-bar">
      <nav class="admin-nav">
        <a class="admin-nav-link dashboard" href="#">
          <svg class="icon icon-person"><use xlink:href="#person" /></svg>
        </a>
        <a href="/logout" class="nav-bar-logout" title="Logout">
          <svg><use xlink:href="#logout"/></svg>
        </a>
        <a href="#" class="nav-bar-account">
          <svg class="harvey-mark"><use xlink:href="#harvey-mark" /></svg>
        </a>
      </nav>
    </div>

    <div class="main-content">
      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">
            Appointments
            <a href="/schedule" class="button main-action">New Appointment</a>
          </h1>
        </div>
      </div>
      <TableData :allTableData="tableData" />
    </div>
    <Flyout>
      <PatientInput
        :classes="['flyout__section']"
        :type="appointmentModType"
        :usertype="userType"
        v-model="selectedRowData.patientName"
      >
        <div><a :href="'mailto:' + selectedRowData.patientEmail">{{ selectedRowData.patientEmail }}</a></div>
        <div><a :href="'tel:' + selectedRowData.patientPhone">{{ selectedRowData.patientPhone | formatPhone }}</a></div>
      </PatientInput>
    </Flyout>
  </div>
</template>

<script>
  // Components
  import AppointmentsWrapper from './_components/AppointmentsWrapper.vue';
  import Flyout from '../dashboard/_components/Flyout.vue';
  import PatientInput from '../_components/PatientInput.vue';
  import TableData from '../_components/TableData.vue';

  // Helpers
  import { capitalize, phone, hyperlink } from '../../filters/textformat.js';
  import Contact from '../../mixins/Contact';
  import combineAppointmentDetails from '../../helpers/getAppointmentDetails.js';
  import moment from 'moment';

  export default {
    name: 'appointments',
    props: ['user', 'patient'],
    components: {
      AppointmentsWrapper,
      Flyout,
      PatientInput,
      TableData
    },
    data() {
      return {
        _appointmentDetails: [],
        appointmentModType: null,
        apiParameters: 'include=patient.user',
        dataCollected: false,
        selectedRowData: {
          patientName: ''
        },
        tableFilterAll: true,
        tableFilterCompleted: false,
        tableFilterUpcoming: false,
        userType: Laravel.user.userType,
      };
    },
    computed: {
      appointmentDetails() {
        return this._appointmentDetails;
      },
      selectedTableData() {
        return this.selectedRowData;
      },
      tableData() {
        return this.dataCollected
          ? this.appointmentTablePrep(this.appointmentDetails)
          : [];
      }
    },
    methods: {
      appointmentTablePrep(appointmentData) {
        return appointmentData.map(appt => {
          return {
            formatted: {
              'Date': {
                'value': moment(appt.attributes.appointment_at.date).format('ddd MMM Do'),
                'width': '10%' },
              'Time': {
                'value': moment(appt.attributes.appointment_at.date).format('h:mm a'),
                'width': '10%' },
              'Client': {
                'value': `${capitalize(appt.patientData.first_name)} ${capitalize(appt.patientData.last_name)}`,
                'width': '15%' },
              'Doctor': {
                'value': `Dr. ${appt.attributes.practitioner_name}`,
                'width': '15%' },
              'Type': {
                'value': 'ND',
                'width': '10%' },
              'Purpose': {
                'value': appt.attributes.reason_for_visit,
                'width': '30%' },
              'Rate': {
                'values': '$150',
                'width': '10%'
              }
            },
            raw: appt
          }
        });
      }
    },
    filters: {
      formatPhone(num) {
        return phone(num);
      }
    },
    created() {
      axios.get(`${this.$root.apiUrl}/appointments?${this.apiParameters}`).then(response => {
        this._appointmentDetails = combineAppointmentDetails(response.data);
        this.dataCollected = true;
      })
    },
    mounted() {
      this.$eventHub.$on('rowClickEvent', (rowData, rowIsActive) => {
        // this.selectedRowData.patientName = `${capitalize(rowData.patientData.first_name)} ${capitalize(rowData.patientData.last_name)}`;
        this.selectedRowData = {
          appointmentDay: moment(rowData.attributes.appointment_at.date).format('ddd MMM Do'),
          appointmentStatus: 'Pending', // Still need this from
          appointmentTime: moment(rowData.attributes.appointment_at.date).format('h:mm a'),
          doctorName: `Dr. ${rowData.attributes.practitioner_name}`,
          patientEmail: rowData.patientData.email,
          patientName: `${capitalize(rowData.patientData.first_name)} ${capitalize(rowData.patientData.last_name)}`,
          patientPhone: rowData.patientData.phone
        }
        this.$eventHub.$emit('appointmentSelected', this.appointmentDetails, rowIsActive);
      })
    }
  }
</script>
