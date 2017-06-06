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

          <FilterButtons
            :active-filter="activeFilter"
            :filters="filters"
            :loading="$root.$data.global.loadingAppointments"
            :on-filter="handleFilter"
          />

        </div>
      </div>

      <AppointmentTable
        :handle-row-click="handleRowClick"
        :loading="$root.$data.global.loadingAppointments"
        :refresh="$root.getAppointments"
        :reset="() => appointments = $root.$data.global.appointments"
        :selected-row="selectedRowData"
        :tableRowData="appointments"
      />

    </div>

    <Flyout
      :active="flyoutActive"
      :heading="flyoutHeading"
      :on-close="handleFlyoutClose"
    >

      <Patient
        :editable="editablePatient"
        :email="appointment.patientEmail"
        :list="patientList"
        :name="appointment.patientName"
        :phone="appointment.patientPhone"
        :set-patient="setPatientInfo"
        :visible="visiblePatient"
      />

      <Practitioner
        :editable="editablePractitioner"
        :name="appointment.practitionerName"
        :list="practitionerList"
        :set-practitioner="setPractitionerInfo"
        :visible="visiblePractitioner"
      />

      <Days
        :day="this.appointment.day"
        :editable="editableDays"
        :is-loading="loadingDays"
        :list="appointment.practitionerAvailability"
        :mode="flyoutMode"
        :set-times="setAvailableTimes"
        :time="appointment.currentDate"
        :no-availability="noAvailability"
      />

      <Times
        :current-time="appointment.currentDate"
        :editable="editableDays"
        :is-loading="loadingDays"
        :list="appointment.availableTimes"
        :set-time="setTime"
        :time="appointment.time"
      />

      <Status
        :editable="editableStatus"
        :list="statuses"
        :set-status="setStatus"
        :status="appointment.status"
        :visible="visibleStatus"
      />

      <Purpose
        :editable="editablePurpose"
      />

      <div class="inline-centered">

        <button
          v-if="visibleNewButton"
          class="button"
          @click="handleConfirmationModal('new')"
          :disabled="disabledNewButton">Book Appointment</button>

        <button
          v-if="visibleUpdateButtons"
          class="button"
          @click="handleConfirmationModal('update')"
          :disabled="disableUpdateButton">Update Appointment</button>

        <a v-if="visibleUpdateButtons"
          href="#"
          @click.prevent="handleConfirmationModal('cancel')"
          class="input__linkcta">Cancel Appointment</a>

      </div>

    </Flyout>

    <Overlay
      :active="overlayActive"
      :on-click="handleOverlayClick"
    />

    <Modal
      :active="modalActive"
      :container-class="'appointment-modal'"
      :on-close="handleModalClose"
    >
      <h3 class="modal-header">{{ userActionTitle }}</h3>
      <table border="0" style="width: 100%" cellpadding="0" cellspacing="0">
        <tr v-if="userType !== 'patient'">
          <td width="25%" style="min-width: 7em;"><p><strong>Client:</strong></p></td>
          <td><p>{{ appointment.patientName }}</p></td>
        </tr>
        <tr v-if="userType !== 'practitioner'">
          <td width="25%"><p><strong>Doctor:</strong></p></td>
          <td><p>{{ appointment.practitionerName }}</p></td>
        </tr>
        <tr>
          <td width="25%"><p><strong>Time:</strong></p></td>
          <td><p>{{ appointment.date | confirmDate }}</p></td>
        </tr>
        <tr v-if="flyoutMode === 'update'">
          <td width="25%"><p><strong>Status:</strong></p></td>
          <td><p>{{ appointment.status | confirmStatus }}</p></td>
        </tr>
        <tr>
          <td width="25%"><p><strong>Purpose:</strong></p></td>
          <td><p>{{ appointment.purpose | confirmPurpose }}</p></td>
        </tr>
      </table>
      <div class="modal-button-container">
        <button class="button" @click="handleUserAction">Yes, Confirm</button>
        <button class="button button--cancel" @click="modalActive = false">Go Back</button>
        <p v-if="userAction !== 'cancel'">You will receive an email confirmation of your updated appointment. We will send you another notification one hour before your appointment.</p>
      </div>
    </Modal>

    <NotificationPopup
      :active="notificationActive"
      :comes-from="notificationDirection"
      :symbol="notificationSymbol"
      :text="notificationMessage"
    />

  </div>
</template>

<script>
// components
import AppointmentTable from './components/AppointmentTable.vue';
import Days from './components/Days.vue';
import FilterButtons from '../../commons/FilterButtons.vue';
import Flyout from '../../commons/Flyout.vue';
import Modal from '../../commons/Modal.vue';
import NotificationPopup from '../../commons/NotificationPopup.vue';
import Overlay from '../../commons/Overlay.vue';
import Patient from './components/Patient.vue';
import Practitioner from './components/Practitioner.vue';
import Purpose from './components/Purpose.vue';
import Status from './components/Status.vue';
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
      name: 'appointments',
      appointment: {
        availableTimes: [],
        date: '',
        day: '',
        currentDate: '',
        currentPurpose: '',
        currentStatus: '',
        id: '',
        status: '',
        patientEmail: '',
        patientId: '',
        patientName: '',
        patientPhone: '',
        practitionerAvailability: [],
        practitionerId: '',
        practitionerName: '',
        purpose: '',
        time: '',
      },
      activeFilter: 0,
      appointments: [],
      cache: {
        all: [],
        upcoming: [],
        completed: []
      },
      filters: ['All', 'Upcoming', 'Completed'],
      flyoutActive: false,
      flyoutHeading: '',
      flyoutMode: null,
      loadingDays: false,
      loadingPatients: !this.$root.$data.global.patients.length,
      loadingTableData: true,
      modalActive: false,
      noAvailability: false,
      notificationActive: false,
      notificationDirection: 'top-right',
      notificationDuration: 3000,
      notificationMessage: '',
      notificationSymbol: '&#10003;',
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
      tableEmptyMsg: '',
      tableColumns,
      tableLoadingMsg: 'Loading appointment data...',
      userAction: '',
      userActionTitle: '',
      userType: Laravel.user.userType
    }
  },
  name: 'appts',
  components: {
    AppointmentTable,
    Days,
    FilterButtons,
    Flyout,
    Modal,
    NotificationPopup,
    Overlay,
    Patient,
    Practitioner,
    Purpose,
    Status,
    Times,
    UserNav
  },
  filters: {
    confirmDate(date) {
      return toLocal(date, 'dddd, MMMM Do [at] h:mm a');
    },
    confirmPurpose(purpose) {
      return purpose.length ? purpose : 'New appointment';
    },
    confirmStatus(status) {
      return convertStatus(status);
    }
  },
  computed: {
    disabledNewButton() {
      return this.flyoutMode === 'new' &&
        (!this.appointment.date || (!this.appointment.patientId && this.userType !== 'patient'));
    },
    disableUpdateButton() {
      return this.flyoutMode === 'update'
        && (
           (this.appointment.date === '' || this.appointment.date === this.appointment.currentDate) &&
           (this.appointment.purpose === this.appointment.currentPurpose) &&
           (this.appointment.status === this.appointment.currentStatus)
        )
    },
    editableDays() {
      if (this.flyoutMode === 'new') return true;
      if (this.userType === 'patient' && this.appointment.status !== 'pending') return false;
      return this.checkPastAppointment();
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
      if (this.userType === 'patient' && this.appointment.status !== 'pending') return false;
      return this.checkPastAppointment();
    },
    emptyTableMsg() {
      return this.tableEmptyMsg;
    },
    visibleNewButton() {
      return this.flyoutMode === 'new';
    },
    visibleStatus() {
      return this.flyoutMode !== 'new';
    },
    visiblePatient() {
      return this.userType !== 'patient';
    },
    visiblePractitioner() {
      return this.userType !== 'practitioner';
    },
    visibleUpdateButtons() {
      return this.flyoutMode === 'update' &&
        (this.userType !== 'patient') ||
        (this.userType === 'patient' && this.checkPastAppointment()) &&
        (this.userType === 'patient' && this.appointment.status === 'pending')
    },
  },

  methods: {

    checkPastAppointment() {
      return moment.utc(this.appointment.currentDate).local().diff(moment()) > 0;
    },

    checkTableData() {
      if (!this.appointments.length) {
        this.tableEmptyMsg = 'No appointments found';
      } else {
        this.tableEmptyMsg = '';
      }
    },

    // Get availability for appointment practitioner
    getAvailability(id) {
      if (this.editableDays) this.loadingDays = true;
      this.noAvailability = false;
      axios.get(`/api/v1/practitioners/${id}?include=availability`).then(response => {
        let list = transformAvailability(response.data.meta.availability);
        this.appointment.practitionerAvailability = list
          .filter(obj => obj.times.length)
          .map(obj => {
            return { value: moment(obj.date).format('dddd, MMMM Do'), data: obj };
          });
        // if no availabilty, show warning message
        if (!list.filter(obj => obj.times.length).length) {
          this.noAvailability = true;
          this.loadingDays = false;
        // else turn off loading display
        } else {
          this.loadingDays = false;
        }
      });
    },

    // When user clicks flyout button
    handleConfirmationModal(action) {
      this.userAction = action;
      console.log(moment.utc('2017-06-13 00:30:00').local().format('YYYY-MM-DD HH:mm:ss'))
      switch(action) {
        case 'cancel':
          this.userActionTitle = 'Confirm Cancellation';
          this.appointment.status = 'canceled';
          this.appointment.date = this.appointment.currentDate;
          break;
        case 'update':
          this.userActionTitle = 'Confirm Update';
          if (this.appointment.status === 'canceled' ||
             (this.userType !== 'patient' && this.appointment.date === '')) {
            this.appointment.date = this.appointment.currentDate;
          }
          break;
        case 'new':
          this.userActionTitle = 'Confirm Appointment';
          this.appointment.status = 'pending';
          break;
      }
      this.modalActive = true;
    },

    handleFilter(name, index) {
      this.activeFilter = index;
      switch(name) {
        case 'All':
          this.appointments = this.cache.all;
          break;
        case 'Upcoming':
          this.appointments = this.cache.upcoming;
          break;
        case 'Completed':
          this.appointments = this.cache.completed;
          break;
      }
      this.checkTableData();
    },

    handleFlyoutClose() {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
      this.handleRowClick(null, null);
      setTimeout(this.resetAppointment, 300);
    },

    handleModalClose() {
      this.modalActive = false;
    },

    // Setup flyout and appointment info on new appointment click
    handleNewAppointmentClick() {

      this.resetAppointment();

      // Even though the practitioner isn't shown, we still need the information
      // to grab availability
      // this.loadingDays = true;
      if (this.userType === 'practitioner') {
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

    handleNotificationInit() {
      this.notificationActive = true;
      setTimeout(() => this.notificationActive = false, this.notificationDuration);
    },

    handleOverlayClick() {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
      setTimeout(this.resetAppointment, 300);
    },

    handleRowClick(obj, index) {
      let data;
      if (obj) {
        data = obj.data === this.selectedRowData ? null : obj.data;
      } else {
        data = null;
      }

      // Initial resets for if flyout is already open
      this.appointment.date = '';
      this.appointment.currentDate = '';
      this.appointment.availableTimes = [];

      if (data) {
        this.selectedRowData = data;
        this.appointments.forEach((obj, i) => {
          this.selectedRowIndex = this.selectedRowData === obj.data ? null : i;
        })
        // appointment id
        this.appointment.id = data._appointmentId;

        // patient info
        this.appointment.patientEmail = data._patientEmail;
        this.appointment.patientName = `${data._patientLast}, ${data._patientFirst}`;
        this.appointment.patientPhone = data._patientPhone;
        if (this.userType !== 'patient') this.appointment.patientId = data._patientId;

        // store current date
        this.appointment.currentDate = moment(data._date).format('YYYY-MM-DD HH:mm:ss');
        this.appointment.currentPurpose = data.purpose;
        this.appointment.currentStatus = convertStatus(data.status);

        // set status
        this.appointment.status = convertStatus(data.status);

        // Availability
        if (!this.editableDays) this.loadingDays = false;
        if (!this.appointment.practitionerAvailability.length
            || this.appointment.practitionerId !== data._doctorId) {
          this.appointment.practitionerAvailability = [];
          this.getAvailability(data._doctorId);
        }

        // Practitioner info
        this.appointment.practitionerName = data.doctor;
        this.appointment.practitionerId = data._doctorId;

        // Purpose text
        this.appointment.purpose = data.purpose;
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
    },

    handleUserAction() {
      // Setup
      let data = {
        appointment_at: this.appointment.date,
        reason_for_visit: this.appointment.purpose,
        status: this.appointment.status,
        patient_id: this.appointment.patientId * 1,
        practitioner_id: this.appointment.practitionerId * 1
      }
      const action = this.userAction === 'new' ? 'post' : 'patch';
      const api = this.userAction === 'new' ? '/api/v1/appointments' : `/api/v1/appointments/${this.appointment.id}`;
      const succesPopup = this.userAction !== 'cancel';
      this.notificationMessage = this.userAction === 'new' ? 'Appointment Created!' : 'Appointment Updated!';

      // api constraints
      if (this.userType === 'patient') {
        delete data.patient_id;
      }
      if (this.userAction === 'update') {
        if (this.appointment.currentDate === data.appointment_at) {
          delete data.appointment_at;
        }
        delete data.patient_id;
      }
      if (this.userAction !== 'new') {
        delete data.practitioner_id;
      }
      if (this.userAction === 'cancel') {
        delete data.appointment_at;
        delete data.patient_id;
      }

      // Make the call
      // TO-DO: Add error notifications if api call fails
      axios[action](api, data).then(response => {
        this.$root.getAppointments();
        if (succesPopup) this.handleNotificationInit();
      }).catch(err => console.error(err.response));

      // Resets
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.modalActive = false;
      this.overlayActive = false;
      this.selectedRowData = null;
      this.selectedRowIndex = null;
      setTimeout(this.resetAppointment, 300);
    },

    resetAppointment() {
      this.appointment.availableTimes = [];
      this.appointment.date = '';
      this.appointment.day = '';
      this.appointment.currentDate = '';
      this.appointment.currentPurpose = '';
      this.appointment.currentStatus = '';
      this.appointment.id = '';
      this.appointment.status = '';
      this.appointment.patientEmail = '';
      this.appointment.patientId = '';
      this.appointment.patientName = '';
      this.appointment.patientPhone = '';
      this.appointment.practitionerAvailability = [];
      this.appointment.practitionerId = '';
      this.appointment.practitionerName = '';
      this.appointment.purpose = '';
      this.appointment.time = '';
      this.selectedRowData = null;
      this.selectedRowIndex = null;
      this.noAvailability = false;
    },

    setAvailableTimes(value, index) {
      this.appointment.day = value;
      this.appointment.availableTimes = [];
      this.appointment.availableTimes = this.appointment.day
        ? this.appointment.practitionerAvailability[index - 1].data.times
        : [];
    },

    // Set patient info with data from list object
    setPatientInfo(data) {
      this.appointment.patientEmail = data.email;
      this.appointment.patientName = data.name;
      this.appointment.patientId = data.id;
      this.appointment.patientPhone = data.phone;
    },

    setupAppointments(list) {
      this.appointments = tableDataTransform(list);
      this.cache.all = this.appointments;
      this.cache.upcoming = this.appointments.filter(obj => obj.data.status === 'Pending');
      this.cache.completed = this.appointments.filter(obj => obj.data.status === 'Complete');
      Vue.nextTick(() => {
        this.checkTableData();
      })
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
    },

    setupPractitionerList(list) {
      this.practitionerList = list.map(obj => {
        return { value: obj.name, data: obj };
      });
    },

    setStatus(status) {
      this.appointment.status = status.data;
    },

    setTime(timeObj) {
      if (timeObj) {
        this.appointment.time = toLocal(timeObj.stored, 'h:mm a');
        this.appointment.date = timeObj.utc.format('YYYY-MM-DD HH:mm:ss');
      } else {
        this.appointment.time = '';
        this.appointment.date = '';
      }
    }

  },

  mounted() {

    // If data from app.js has loaded prior to mount, set data
    const patients = this.$root.$data.global.patients;
    const practitioners = this.$root.$data.global.practitioners;
    if (patients.length) this.setupPatientList(patients);
    if (practitioners.length) this.setupPractitionerList(practitioners);

    if (this.$root.$data.global.appointments.length) {
      this.setupAppointments(this.$root.$data.global.appointments);
    }

    this.$eventHub.$on('receivedAppointments', list => {
      this.setupAppointments(list);
    });

    // Assign patients to patientList when the Promise resolves
    this.$eventHub.$on('receivedPatients', this.setupPatientList);

    // Assign practitioners to practitionerList when Promise resolves
    this.$eventHub.$on('receivedPractitioners', this.setupPractitionerList);

    // For when user inputs into purpose textarea
    this.$eventHub.$on('setPurpose', text => {
      this.appointment.purpose = text;
    });

  },
  destroyed() {
    this.$eventHub.$off('receivedAppointments');
    this.$eventHub.$off('receivedPatients');
    this.$eventHub.$off('receivedPractitioners');
    this.$eventHub.$off('setPurpose');
  }
}
</script>
