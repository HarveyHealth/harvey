<template>
  <div class="main-container">

    <UserNav />

    <div class="main-content">

      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">
            <span class="text">Your Appointments</span>
            <button class="button main-action circle" @click="handleNewAppointmentClick">
              <svg><use xlink:href="#addition"/></svg>
            </button>
          </h1>
        </div>
      </div>

      <TableData :columns="tableColumns" :tabledata="appointments" />

    </div>

    <Flyout :active="flyoutActive" :heading="flyoutHeading">

      <Patient
        :editable="editablePatient"
        :email="appointment.patientEmail"
        :list="patientList"
        :name="appointment.patientName"
        :phone="appointment.patientPhone"
        :visible="visiblePatient"
      />

      <Practitioner
        :editable="editablePractitioner"
        :name="appointment.practitionerName"
        :list="practitionerList"
        :visible="visiblePractitioner"
      />

      <Days
        :editable="editableDays"
        :list="appointment.practitionerAvailability"
        :mode="flyoutMode"
        :time="appointment.currentDate"
        :noavailability="noAvailability"
      />

      <Times
        :editable="editableDays"
        :isloading="loadingDays"
        :list="appointment.availableTimes"
        :time="appointment.currentDate"
      />

      <Status
        :editable="editableStatus"
        :list="statuses"
        :status="appointment.status"
        :visible="visibleStatus"
      />

      <Purpose
        :editable="editablePurpose"
      />

    </Flyout>

    <Overlay :active="overlayActive" />

  </div>
</template>

<script>
// components
import Days from './components/Days.vue';
import Flyout from '../../commons/Flyout2.vue';
import Overlay from '../../commons/Overlay2.vue';
import Patient from './components/Patient.vue';
import Practitioner from './components/Practitioner.vue';
import Purpose from './components/Purpose.vue';
import Status from './components/Status.vue';
import TableData from '../../commons/TableData.vue';
import Times from './components/Times.vue';
import UserNav from '../../commons/UserNav.vue';

// other
import convertStatus from './convertStatus';
import moment from 'moment';
import tableColumns from './tableColumns';
import tableDataTransform from './tableDataTransform';
import transformAvailability from '../../utils/methods/transformAvailability';
import toLocal from '../../utils/methods/toLocal';

export default {
  data() {
    return {
      appointment: {
        availableTimes: '',
        date: '',
        currentDate: '',
        id: '',
        status: '',
        patientEmail: '',
        patientId: '',
        patientName: '',
        patientPhone: '',
        practitionerAvailability: '',
        practitionerId: '',
        practitionerName: '',
        purpose: ''
      },
      flyoutActive: false,
      flyoutHeading: '',
      flyoutMode: null,
      loadingDays: true,
      noAvailability: false,
      overlayActive: false,
      patientList: [],
      practitionerList: [],
      selectedRowData: null,
      selectedRowIndex: null,
      statuses: [
        { value: 'Pending', data: 'pending' },
        { value: 'No-Show-Patient', data: 'no_show_patient' },
        { value: 'No-Show-Doctor', data: 'no_show_doctor' },
        { value: 'Canceled', data: 'canceled' },
        { value: 'Complete', data: 'complete' }
      ],
      tableColumns,
      userType: Laravel.user.userType
    }
  },
  name: 'appts',
  components: {
    Days,
    Flyout,
    Overlay,
    Patient,
    Practitioner,
    Purpose,
    Status,
    TableData,
    Times,
    UserNav
  },
  computed: {
    appointments() {
      return tableDataTransform(this.$root.$data.global.appointments);
    },
    editableDays() {
      if (this.flyoutMode === 'new') return true;
      return moment.utc(this.appointment.currentDate).local().diff(moment()) > 0;
    },
    editableStatus() {
      return this.userType !== 'patient';
    },
    editablePatient() {
      return this.userType !== 'patient' && this.flyoutMode === 'new';
    },
    editablePractitioner() {
      return this.userType !== 'practitioner' && this.flyoutMode === 'new';
    },
    editablePurpose() {
      if (this.flyoutMode === 'new') return true;
      return moment.utc(this.appointment.currentDate).local().diff(moment()) > 0;
    },
    visibleStatus() {
      return this.flyoutMode !== 'new';
    },
    visiblePatient() {
      return this.userType !== 'patient';
    },
    visiblePractitioner() {
      return this.userType !== 'practitioner';
    }
  },

  methods: {

    // Get availability for appointment practitioner
    getAvailability(id) {
      if (this.editableDays) this.loadingDays = true;
      axios.get(`/api/v1/practitioners/${id}?include=availability`).then(response => {
        this.appointment.practitionerAvailability = transformAvailability(response.data.meta.availability);
        // if no availabilty, show warning message
        if (!this.appointment.practitionerAvailability.filter(obj => obj.times.length).length) {
          this.noAvailability = true;
        // else turn off loading display
        } else {
          this.loadingDays = false;
        }
      });
    },

    // Setup flyout and appointment info on new appointment click
    handleNewAppointmentClick() {

      this.resetAppointment();

      if (this.userType !== 'patient' && this.patientList.length) {
        this.setPatientInfo(this.patientList[0].data);
      }

      // Even though the practitioner isn't shown, we still need the information
      // to grab availability
      this.loadingDays = true;
      if (this.practitionerList.length) {
        this.setPractitionerInfo(this.practitionerList[0].data);
      }

      this.appointment.status = 'pending';
      this.appointment.purpose = 'New appointment';
      this.$eventHub.$emit('forcePurposeText', this.appointment.purpose);

      this.flyoutHeading = 'Book Appointment';
      this.flyoutMode = 'new';
      this.flyoutActive = true;
      this.overlayActive = true;
      this.selectedRowData = null;
      this.selectedRowIndex = null;
      this.$eventHub.$emit('tableRowUnselect');
    },

    resetAppointment() {
      for (var key in this.appointment) {
        this.appointment[key] = '';
      }
      this.noAvailability = false;
      this.$eventHub.$emit('forceDaySelect', '');
      this.$eventHub.$emit('forceTimeSelect', '');
    },

    // Set patient info with data from list object
    setPatientInfo(data) {
      this.appointment.patientEmail = data.email;
      this.appointment.patientName = data.name;
      this.appointment.patientId = data.id;
      this.appointment.patientPhone = data.phone;
    },

    // Set practitioner info with data from list object
    setPractitionerInfo(data) {
      this.appointment.practitionerId = data.id;
      this.appointment.practitionerName = data.name;
      this.getAvailability(this.appointment.practitionerId);
    },

    setupPatientList(list) {
      this.patientList = list.map(item => {
        return { value: item.name, data: item };
      });
      // If flyout mode is new, add patient info
      if (this.userType !== 'patient' && this.flyoutMode === 'new') {
        this.setPatientInfo(this.patientList[0].data);
      }
    },

    setupPractitionerList(list) {
      this.practitionerList = list.map(obj => {
        return { value: obj.name, data: obj };
      });
      // If flyout mode is new, add practitioner info
      if (this.flyoutMode === 'new') {
        this.setPractitionerInfo(this.practitionerList[0].data);
      }
    },

  },

  mounted() {

    // If data from app.js has loaded prior to mount, set data
    const patients = this.$root.$data.global.patients;
    const practitioners = this.$root.$data.global.practitioners;
    if (patients.length) this.setupPatientList(patients);
    if (practitioners.length) this.setupPractitionerList(practitioners);

    // When the flyout closes, close the overlay and unselect any table rows
    this.$eventHub.$on('closeFlyout', () => {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
      this.selectedRowData = null;
      this.selectedRowIndex = null;
      this.$eventHub.$emit('tableRowUnselect');
      setTimeout(this.resetAppointment, 300);
    });

    // Assign patients to patientList when the Promise resolves
    this.$eventHub.$on('receivedPatients', this.setupPatientList);

    // Assign practitioners to practitionerList when Promise resolves
    this.$eventHub.$on('receivedPractitioners', this.setupPractitionerList);

    // For when a day is selected
    this.$eventHub.$on('selectDay', dayObj => {
      // If the user selects the empty option it returns false
      // If the mode is update then we want to revert back to the current date
      this.$eventHub.$emit('forceTimeSelect', '');
      if (dayObj) {
        this.appointment.availableTimes = dayObj.data.times;
      } else {
        this.appointment.availableTimes = '';
        this.appointment.date = '';
      }
    });

    // For when a status is selected
    this.$eventHub.$on('selectStatus', status => {
      this.appointment.status = status.data;
    });

    // For when a patient is selected from the dropdown
    this.$eventHub.$on('selectPatient', patient => {
      this.setPatientInfo(patient.data);
    });

    // For when a practitioner is selected from the dropdown
    this.$eventHub.$on('selectPractitioner', practitioner => {
      this.loadingDays = true;
      this.noAvailability = false;
      this.appointment.practitionerAvailability = '';
      this.appointment.availableTimes = '';
      this.setPractitionerInfo(practitioner.data);
    });

    // For when a time is selected
    this.$eventHub.$on('selectTime', timeObj => {
      if (timeObj) {
        this.appointment.date = timeObj.data.utc.format('YYYY-MM-DD HH:mm:ss');
      } else {
        this.appointment.date = '';
      }
    });

    // For when user inputs into purpose textarea
    this.$eventHub.$on('setPurpose', text => {
      this.appointment.purpose = text;
    });

    // On row click setup appointment information and call flyout
    this.$eventHub.$on('tableRowClick', (obj, index) => {

      this.selectedRowData = obj;
      this.selectedRowIndex = index;

      // Initial resets for if flyout is already open
      this.appointment.date = '';
      this.appointment.currentDate = '';
      this.appointment.availableTimes = '';

      this.$eventHub.$emit('forceDaySelect', '');
      this.$eventHub.$emit('forceTimeSelect', '');

      if (obj) {
        // appointment id
        this.appointment.id = obj.rowData._appointmentId;

        // patient info
        this.appointment.patientEmail = obj.rowData._patientEmail;
        this.appointment.patientName = `${obj.rowData._patientLast}, ${obj.rowData._patientFirst}`;
        this.appointment.patientPhone = obj.rowData._patientPhone;
        if (this.userType !== 'patient') this.appointment.patientId = obj.rowData._patientId;

        // store current date
        this.appointment.currentDate = obj.rowData._date;

        // set status
        this.$eventHub.$emit('forceStatusSelect', obj.rowData.status);
        this.appointment.status = convertStatus(obj.rowData.status);

        // Availability
        if (!this.editableDays) this.loadingDays = false;
        if (!this.appointment.practitionerAvailability.length
            || this.appointment.practitionerId !== obj.rowData._doctorId) {
          this.appointment.practitionerAvailability = [];
          this.getAvailability(obj.rowData._doctorId);
        }

        // Practitioner info
        this.appointment.practitionerName = obj.rowData.doctor;
        this.appointment.practitionerId = obj.rowData._doctorId;

        // Purpose text
        this.appointment.purpose = obj.rowData.purpose;
        this.$eventHub.$emit('forcePurposeText', this.appointment.purpose);

        // Activate flyout
        this.flyoutHeading = 'Update Appointment';
        this.flyoutMode = 'update';
        this.flyoutActive = true;

      } else {

        // Reset everything
        this.flyoutActive = false;
        this.flyoutMode = null;
        this.selectedRowData = null;
        this.selectedRowIndex = null;
        setTimeout(this.resetAppointment, 300);
      }
    });

    // When the overlay is closed because it was clicked, close the flyout
    this.$eventHub.$on('overlayClicked', () => {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
      setTimeout(this.resetAppointment, 300);
    });

    window.check = () => {
      console.log(this.appointment);
    }

  },
  destroyed() {
    this.$eventHub.$off('closeFlyout');
    this.$eventHub.$off('receivedPatients');
    this.$eventHub.$off('receivedPractitioners');
    this.$eventHub.$off('overlayClicked');
    this.$eventHub.$off('selectDay');
    this.$eventHub.$off('selectPatient');
    this.$eventHub.$off('selectPractitioner');
    this.$eventHub.$off('selectStatus');
    this.$eventHub.$off('selectTime');
    this.$eventHub.$off('tableRowClick');
  }
}
</script>
