<template>
  <div class="main-container">
    <div class="main-content">
      <div class="main-header">
        <div class="container container-backoffice">
          <h1 class="title header-xlarge">
            <span class="text">Your Appointments</span>
            <button class="button main-action circle" @click="handleNewAppointmentClick">
              <svg><use xlink:href="#addition"/></svg>
            </button>
          </h1>

          <FilterButtons
            :active-filter="activeFilter"
            :filters="filters"
            :loading="disabledFilters"
            :on-filter="handleFilter"
          />

        </div>
      </div>

      <AppointmentTable
        :handle-row-click="handleRowClick"
        :loading="$root.$data.global.loadingAppointments"
        :selected-row="selectedRowData"
        :updating-row="selectedRowUpdating"
        :updated-row="selectedRowHasUpdated"
        :tableRowData="appointments"
      />

    </div>

    <Flyout
      :active="flyoutActive"
      :heading="flyoutHeading"
      :on-close="handleFlyoutClose"
    >

      <Patient
        :address="appointment.patientAddress"
        :editable="editablePatient"
        :email="appointment.patientEmail"
        :list="patientList"
        :name="patientDisplay"
        :phone="appointment.patientPhone"
        :set-patient="setPatientInfo"
        :visible="visiblePatient"
      />

      <Practitioner
        :editable="editablePractitioner"
        :is-disabled="!appointment.patientId"
        :name="appointment.practitionerName"
        :list="practitionerList"
        :set-practitioner="setPractitionerInfo"
        :visible="visiblePractitioner"
      />

      <div class="input__container" v-if="appointment.patientAddress && flyoutMode === 'update'">
        <label class="input__label">Mailing Address</label>
        <p class="input__item" v-html="appointment.patientAddress"></p>
      </div>

      <div class="input__container" v-if="editableDays && flyoutMode === 'update'">
        <label class="input__label">Appointment</label>
        <span class="input__item">{{ appointment.currentDate | confirmDate }}</span>
        <div class="input--warning" v-if="currentDateUnavailable">Previous time unavailable. Please select a new day and time.</div>
      </div>

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
        :character-limit="purposeCharLimit"
        :editable="editablePurpose"
        :on-input="handlePurposeInput"
        :text-value="appointment.purpose"
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

        <a v-if="visibleCancelButton"
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
      :hide-close="isHandlingAction"
    >
      <h3 class="modal-header" v-show="!isHandlingAction">{{ userActionTitle }}</h3>
      <div v-show="isHandlingAction" style="text-align: center; font-size: 22px;">
        <p>Updating appointments</p><br>
        <div style="width: 24px; margin: 0 auto;">
          <ClipLoader :size="'24px'" :color="'#4f6268'" />
        </div>
      </div>
      <p class="error-text" v-show="bookingConflict">We&rsquo;re sorry, it looks like that date and time was recently booked. Please take a look at other available times.</p>
      <table border="0" style="width: 100%" cellpadding="0" cellspacing="0" v-show="!bookingConflict && !isHandlingAction">
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
          <td><p>{{ appointment.purpose }}</p></td>
        </tr>
      </table>
      <div class="modal-button-container" v-show="!bookingConflict && !isHandlingAction">
        <button class="button" @click="handleUserAction">Yes, Confirm</button>
        <button class="button button--cancel" @click="handleModalClose">Go Back</button>
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

// other
import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
import convertStatus from './utils/convertStatus';
import moment from 'moment';
import tableColumns from './utils/tableColumns';
import tableDataTransform from './utils/tableDataTransform';
import tableSort from '../../utils/methods/tableSort';
import transformAvailability from '../../utils/methods/transformAvailability';
import toLocal from '../../utils/methods/toLocal';

export default {
  props: {
    appt_id: String
  },
  data() {
    return {
      activeFilter: 0,
      appointment: this.resetAppointment(),
      appointments: [],
      bookingConflict: false,
      cache: {
        all: [],
        upcoming: [],
        completed: []
      },
      filters: ['All', 'Upcoming', 'Completed'],
      flyoutActive: false,
      flyoutHeading: '',
      flyoutMode: null,
      isHandlingAction: false,
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
      patientDisplay: '',
      patientList: [],
      practitionerList: [],
      purposeCharLimit: 180,
      selectedRowData: null,
      selectedRowHasUpdated: null,
      selectedRowIndex: null,
      selectedRowUpdating: null,
      statuses: [
        { value: 'Pending', data: 'pending' },
        { value: 'No-Show-Patient', data: 'no_show_patient' },
        { value: 'No-Show-Doctor', data: 'no_show_doctor' },
        { value: 'General Conflict', data: 'general_conflict' },
        { value: 'Canceled', data: 'canceled' },
        { value: 'Complete', data: 'complete' }
      ],
      userAction: '',
      userActionTitle: '',
      userType: Laravel.user.user_type
    }
  },

  name: 'appointments',

  components: {
    AppointmentTable,
    ClipLoader,
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
  },

  filters: {
    confirmDate(date) {
      return `${toLocal(date, 'dddd, MMMM Do [at] h:mm a')} (${moment.tz(moment.tz.guess()).format('z')})`;
    },
    confirmStatus(status) {
      return convertStatus(status);
    }
  },

  computed: {
    currentDateUnavailable() {
      if (this.appointment.currentStatus !== 'pending') {
        return this.appointment.practitionerAvailability.filter(dayObj => {
          return dayObj.data.times.filter(timeObj => timeObj.stored === this.appointment.currentDate).length;
        }).length === 0;
      } else {
        return false;
      }
    },
    disabledFilters() {
      return this.$root.$data.global.loadingAppointments || this.selectedRowUpdating !== null;
    },
    disabledNewButton() {
      return this.flyoutMode === 'new' &&
        (!this.appointment.date || (!this.appointment.patientId && this.userType !== 'patient'));
    },
    disableUpdateButton() {
      return this.flyoutMode === 'update'
        && (this.currentDateUnavailable && !this.appointment.date) ||
           (
             (this.appointment.date === '' || this.appointment.date === this.appointment.currentDate) &&
             (this.appointment.purpose === this.appointment.currentPurpose) &&
             (this.appointment.status === this.appointment.currentStatus)
           )
    },
    editableDays() {
      if (this.flyoutMode === 'new') return true;
      if (this.userType === 'patient' && this.appointment.status !== 'pending') return false;
      if (this.appointment.status !== 'pending') return false;
      return this.checkPastAppointment();
    },
    editableStatus() {
      return this.userType !== 'patient';
    },
    editablePatient() {
      return this.userType !== 'patient' && this.flyoutMode === 'new';
    },
    editablePractitioner() {
      return (this.flyoutMode === 'new' && this.userType !== 'practitioner') ||
             (this.flyoutMode === 'update' && this.userType === 'admin' && this.appointment.status === 'pending');
    },
    editablePurpose() {
      if (this.flyoutMode === 'new') return true;
      if (this.userType === 'patient' && this.appointment.status !== 'pending') return false;
      return this.checkPastAppointment();
    },
    emptyTableMsg() {
      return this.tableEmptyMsg;
    },
    loadedAppointments() {
      return this.$root.$data.global.loadingAppointments;
    },
    loadedPatients() {
      return this.$root.$data.global.loadingPatients;
    },
    loadedPractitioners() {
      return this.$root.$data.global.loadingPractitioners;
    },
    visibleCancelButton() {
      return (
        this.appointment.currentStatus === 'pending' &&
        this.visibleUpdateButtons &&
        this.userType === 'patient'
      );
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

  // These watches are setup to look at computed properties that receive data from global state.
  // When the global state changes it triggers the computed property and based on the return value
  // these functions will run setup methods for the data necessary to run the Appointments page.
  watch: {
    loadedAppointments(val) {
      if (!val) {
        this.setupAppointments(this.$root.$data.global.appointments);
      }
    },
    loadedPatients(val) {
      if (!val) {
        this.setupPatientList(this.$root.$data.global.patients);
      }
    },
    loadedPractitioners(val) {
      if (!val) {
        this.setupPractitionerList(this.$root.$data.global.practitioners);
      }
    }
  },

  methods: {

    checkPastAppointment() {
      return this.userType === 'patient'
        ? moment.utc(this.appointment.currentDate).local().diff(moment(), 'hours') > 4
        : moment.utc(this.appointment.currentDate).local().diff(moment()) > 0;
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
      // If Days is editable we want to reset loadingDays to see the loading message
      if (this.editableDays) this.loadingDays = true;
      // Reset noAvailability before new data set is fetched
      this.noAvailability = false;
      axios.get(`/api/v1/practitioners/${id}?include=availability`).then(response => {
        // The returned data structure is a bit odd so we need to perform some hefty transformations
        // in order to use it.
        let list = transformAvailability(response.data.meta.availability, this.userType);
        this.appointment.practitionerAvailability = list
          // Remove day objects with no times
          .filter(obj => obj.times.length)
          // Transform into a format the TableData component can consume
          .map(obj => {
            return { value: moment.utc(obj.date).format('dddd, MMMM Do'), data: obj };
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

    // When user clicks the CTA in the flyout for updating, canceling, or creating an appointment.
    // This is the initial setup before the api call is actually made.
    handleConfirmationModal(action) {
      this.userAction = action;
      this.appointment.purpose = this.appointment.purpose || 'New appointment';
      switch(action) {
        case 'cancel':
          this.userActionTitle = 'Confirm Cancellation';
          this.appointment.status = 'canceled';
          this.appointment.date = this.appointment.currentDate;
          break;
        case 'update':
          this.userActionTitle = 'Confirm Update';
          if (this.appointment.status !== 'pending' || this.appointment.date === '') {
            this.appointment.date = this.appointment.currentDate;
          }
          break;
        case 'new':
          if(this.$root.shouldTrack()) {
            // Add "Confirm Appointment" tracking here
          }
          this.userActionTitle = 'Confirm Appointment';
          this.appointment.status = 'pending';
          break;
      }
      this.modalActive = true;
    },

    // When getAppointments is run we save three copies of the data to match
    // the three filters: all, upcoming, and pending. This method just assigns
    // one of these copies to appointments to re-render TableData
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

    // When the flyout closes we want to reset certain pieces of state
    handleFlyoutClose() {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
      this.handleRowClick(null, null);
      setTimeout(() => this.appointment = this.resetAppointment(), 300);
    },

    handleModalClose() {
      if (this.appointment.status === 'canceled') {
        this.appointment.status = this.appointment.currentStatus;
      }
      this.modalActive = false;
      this.bookingConflict = false;
    },


    handleNewAppointmentClick() {

      this.appointment = this.resetAppointment();

      // If the user is a practitioner we only want their information to load for practitioner
      if (this.userType === 'practitioner' && !this.$root.$data.global.loadingPractitioners) {
        this.setPractitionerInfo(this.practitionerList[0].data);
      }

      this.appointment.status = 'pending';
      this.appointment.purpose = 'New appointment';
      this.flyoutHeading = 'Book Appointment';
      this.flyoutMode = 'new';
      this.flyoutActive = true;
      this.overlayActive = true;
      this.selectedRowData = null;
      this.selectedRowIndex = null;
    },

    handleNotificationInit() {
      this.notificationActive = true;
      setTimeout(() => this.notificationActive = false, this.notificationDuration);
    },

    handleOverlayClick() {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
      this.modalActive = false;
      setTimeout(() => this.appointment = this.resetAppointment(), 300);
    },

    handlePurposeInput(e) {
      this.appointment.purpose = e.target.value.substring(0, this.purposeCharLimit);
      // Need to set this manually for some reason. I'm not sure why the bound value does not
      // update with the change to appointment.purpose
      e.target.value = e.target._value;
    },

    handleRowClick(obj, index) {
      // Assign data if new row click, unassign if same row click
      let data;
      if (obj) {
        data = obj.data === this.selectedRowData ? null : obj.data;
      } else {
        data = null;
      }

      // Initial resets for if flyout is already open
      this.appointment.day = '';
      this.appointment.time = '';
      this.appointment.date = '';
      this.appointment.currentDate = '';
      this.appointment.availableTimes = [];

      if (data) {
        this.selectedRowData = data;
        this.selectedRowIndex = index;
        // appointment id
        this.appointment.id = data._appointmentId;

        // patient info
        this.appointment.patientEmail = data._patientEmail;
        this.appointment.patientName = `${data._patientLast}, ${data._patientFirst}`;
        this.appointment.patientPhone = data._patientPhone;
        if (this.userType !== 'patient') this.appointment.patientId = data._patientId;
        this.patientDisplay = `${this.appointment.patientName} (${this.appointment.patientEmail})`;

        // If user is admin, filter practitioners by state licensing regulations
        // First reset the practitioner list
        this.setupPractitionerList(this.$root.$data.global.practitioners);
        this.practitionerList = this.$root.filterPractitioners(this.practitionerList, data.state);

        // patient address
        this.appointment.patientAddress = this.setPatientAddress(data);

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
        this.appointment.currentPractitionerId = data._doctorId;

        // Purpose text
        this.appointment.purpose = data.purpose;

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
        setTimeout(() => this.appointment = this.resetAppointment(), 300);
      }
    },

    handleUserAction() {
      // Setup
      let data = {
        appointment_at: this.appointment.date || this.appointment.currentDate,
        reason_for_visit: this.appointment.purpose,
        status: this.appointment.status,
        patient_id: this.appointment.patientId * 1,
        practitioner_id: this.appointment.practitionerId * 1
      }

      let doctorSwitch = false;
      let action = this.userAction === 'new' ? 'post' : 'patch';
      let api = this.userAction === 'new' ? '/api/v1/appointments' : `/api/v1/appointments/${this.appointment.id}`;
      const succesPopup = this.userAction !== 'cancel';
      this.notificationMessage = this.userAction === 'new' ? 'Appointment Created!' : 'Appointment Updated!';

      // collect data for tracking later
      const appointmentStatus = this.appointment.status;
      const appointmentDate = data.appointment_at;

      // api constraints
      const isPatient = this.userType === 'patient';
      const isPractitioner = this.userType === 'practitioner';
      const isAdmin = this.userType === 'admin';
      const isUpdate = this.userAction === 'update';
      const isCancel = this.userAction === 'cancel';
      const isNew = this.userAction === 'new';
      const hasDoctorSwitch = isUpdate && (this.appointment.currentPractitionerId !== this.appointment.practitionerId);
      const hasStatusSwitch = this.appointment.currentStatus !== this.appointment.status;
      const hasTimeSwitch = this.appointment.currentDate !== this.appointment.date;
      const adminSwitchesDoctor = isAdmin && hasDoctorSwitch;

      // Patients don't need to send up their id
      // Cancellations don't require a patient id
      // Patient id is only required in an update if an admin is switching a doctor
      const shouldRemovePatient = isPatient || isCancel || (isUpdate && (isAdmin && !hasDoctorSwitch));

      // Practitioner id is required for new appointment creations
      // Practitioner id is only required in an update if an admin is switching a doctor
      const shouldKeepPractitioner = isNew || (isUpdate && adminSwitchesDoctor);

      // Time is not necessary when an admin or practitioner updates appointment status or purpose and not Time
      // Time should remain if an admin changes a doctor for an existing appointment
      // Time is not necessary for appointment cancellations
      // const shouldRemoveTime = (!isPatient && isUpdate && (!hasStatusSwitch && !hasDoctorSwitch)) || isCancel;
      const shouldRemoveTime = (isUpdate && !hasTimeSwitch) || (isUpdate && isAdmin && !hasDoctorSwitch) || isCancel;
      const shouldKeepTime = hasTimeSwitch || hasDoctorSwitch;

      if (shouldRemovePatient) delete data.patient_id;
      if (!shouldKeepTime) delete data.appointment_at;
      if (!shouldKeepPractitioner) {
        delete data.practitioner_id;
      } else if (adminSwitchesDoctor) {
        // If the practitioner_id needs to be sent up, it's because an admin is changing the doctor for
        // and already existing appointment. In this case we need to cancel the present appointment using
        // PATCH and then create a new appointment with POST.
        const patchData = { status: 'canceled' };
        axios.patch(`/api/v1/appointments/${this.appointment.id}`, patchData).catch(err => console.log(err.response));
        action = 'post';
        api = '/api/v1/appointments';
      }

      // Reset appointment here so that subsequent row clicks don't get reset after api call
      const apptId = this.appointment.id;
      const oldIds = this.appointments.map(appt => appt.data._appointmentId);
      this.appointment = this.resetAppointment();
      this.isHandlingAction = true;

      // Make the call
      // TO-DO: Add error notifications if api call fails
      axios[action](api, data).then(response => {
        // track the event
        if(this.$root.shouldTrack()) {
          if((this.userType === 'practitioner' || this.userType === 'admin') && appointmentStatus === 'complete') {
            analytics.track('Consultation Completed', {
              date: appointmentDate,
            });
          }
        }

        this.$root.getAppointments(() => {
          Vue.nextTick(() => {
            if (succesPopup) this.handleNotificationInit();
            this.overlayActive = false;
            this.modalActive = false;
            this.isHandlingAction = false;

            const newIds = this.appointments.map(appt => appt.data._appointmentId);
            // if new appointment or doctor switched, find the id that was not in the old ids
            if (isNew || adminSwitchesDoctor) {
              newIds.map((id, index) => {
                if (oldIds.indexOf(id) < 0) {
                  this.selectedRowHasUpdated = index;
                }
              });
            // else if updated existing, find existing
            } else if (isUpdate) {
              this.selectedRowHasUpdated = newIds.indexOf(apptId);
            }
            setTimeout(() => this.selectedRowHasUpdated = null, 2000);
          })
        });
      }).catch(error => {
        if (error.response) console.log(error.response)
        this.selectedRowUpdating = null;
        this.isHandlingAction = false;
        if (this.userAction === 'update' || this.userAction === 'new') {
          this.modalActive = true;
          this.bookingConflict = true;
          this.userActionTitle = 'Booking Conflict';
        }
      });

      this.selectedRowData = null;
      this.flyoutActive = false;
      this.flyoutMode = null;
    },

    resetAppointment() {
      this.noAvailability = false;
      this.patientDisplay = '';
      return {
        availableTimes: [],
        date: '',
        day: '',
        currentDate: '',
        currentPractitionerId: '',
        currentPurpose: '',
        currentStatus: '',
        id: '',
        status: '',
        patientAddress: '',
        patientEmail: '',
        patientId: '',
        patientName: '',
        patientPhone: '',
        practitionerAvailability: [],
        practitionerId: '',
        practitionerName: '',
        purpose: '',
        time: '',
      }
    },

    setAvailableTimes(value, index) {
      // If the day is changed, reset time and date values
      this.setTime(null);
      this.appointment.day = value;
      this.appointment.availableTimes = [];
      this.appointment.availableTimes = this.appointment.day
        // The practitioner index is minus 1 to account for the empty space in the dropdown list
        ? this.appointment.practitionerAvailability[index - 1].data.times
        : [];
    },

    setPatientAddress(data) {
      // We only want to add information to the address if it exists
      let address = '';
      if (data.address_1) address += data.address_1;
      if (data.address_2) address += ` ${data.address_2}`;
      if (data.address_1 && data.city) address += '<br>';
      if (data.city) address += data.city;
      if (data.state) address += ` ${data.state}`;
      if (data.zip) address += `, ${data.zip}`;

      // At the very least, we need to have city and state to display an address
      return data.city && data.state ? address : '';
    },

    // Set patient info with data from list object
    setPatientInfo(data) {
      this.appointment.patientAddress = this.setPatientAddress(data);
      this.appointment.patientEmail = data.email;
      this.appointment.patientName = data.name;
      this.appointment.patientId = data.id;
      this.appointment.patientPhone = data.phone;
      this.patientDisplay = `${data.name} (${data.email})`;

      // If user is admin, filter practitioners by state licensing regulations
      // First reset the practitioner list
      this.setupPractitionerList(this.$root.$data.global.practitioners);
      this.practitionerList = this.$root.filterPractitioners(this.practitionerList, data.state);
    },

    setupAppointments(list) {
      const zone = this.$root.addTimezone();
      const appts = tableDataTransform(list, zone, this.userType).sort(tableSort.byDate('_date')).reverse();
      this.cache.all = appts;
      this.cache.upcoming = appts.filter(obj => obj.data.status === 'Pending');
      this.cache.completed = appts.filter(obj => obj.data.status === 'Complete');

      this.appointments = this.activeFilter === 0
        ? this.cache.all
        : this.activeFilter === 1
          ? this.cache.upcoming
          : this.cache.completed;

      if (this.appt_id) {
        let appt, index;
        this.appointments.forEach((obj, i) => {
          if (obj.data._appointmentId === this.appt_id) {
            index = i;
            appt = obj;
          }
        })
        this.handleRowClick(appt, index);
      }

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
        const display = this.userType === 'patient'
          ? item.name
          : `${item.name} (${item.email})`
        return { value: display, data: item };
      });
    },

    setupPractitionerList(list) {
      this.practitionerList = list.map(obj => {
        return { value: obj.name, data: obj };
      });
      if (this.userType === 'practitioner') {
        this.setPractitionerInfo(this.practitionerList[0].data);
      }
    },

    setStatus(status) {
      this.appointment.status = status.data;
      if (status.data !== 'pending') {
        this.appointment.date = '';
      } else {

      }
    },

    setTime(timeObj) {
      if (timeObj) {
        this.appointment.time = this.$root.addTimezone(moment(timeObj.stored).format('h:mm a'));
        this.appointment.date = timeObj.utc.format('YYYY-MM-DD HH:mm:ss');
      } else {
        this.appointment.time = '';
        this.appointment.date = '';
      }
    }

  },

  mounted() {

    this.$root.$data.global.currentPage = 'appointments';

    // If data from app.js has loaded prior to mount, set data
    const appointments = this.$root.$data.global.appointments;
    const patients = this.$root.$data.global.patients;
    const practitioners = this.$root.$data.global.practitioners;

    if (appointments.length) this.setupAppointments(appointments);
    if (patients.length) this.setupPatientList(patients);
    if (practitioners.length) this.setupPractitionerList(practitioners);

  }
}
</script>
