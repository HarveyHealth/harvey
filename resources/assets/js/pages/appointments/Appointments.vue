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
    <!-- <Flyout :details="appointment_details">
      <PatientInput :classes="['flyout__section']" />
    </Flyout> -->
  </div>
</template>

<script>
  // import Flyout from './_components/Flyout.vue';
  import AppointmentsWrapper from './_components/AppointmentsWrapper.vue';
  import Flyout from '../dashboard/_components/Flyout.vue';
  import PatientInput from '../_components/PatientInput.vue';
  import TableData from '../_components/TableData.vue';

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
        appointmentModNew: false,
        appointmentModUpdate: false,
        apiParameters: 'include=patient.user',
        dataCollected: false,
        selectedRowData: {},
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
    created() {
      axios.get(`${this.$root.apiUrl}/appointments?${this.apiParameters}`).then(response => {
        this._appointmentDetails = combineAppointmentDetails(response.data);
        this.dataCollected = true;
      })
    },
    mounted() {
      this.$eventHub.$on('rowClickEvent', (rowData, rowIsActive) => {
        this.$eventHub.$emit('appointmentSelected', this.appointmentDetails, rowIsActive);
      })
    }
  }
</script>
