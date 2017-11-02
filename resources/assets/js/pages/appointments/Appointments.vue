<template>
  <div class="main-container">
    <div class="main-content">
      <div class="main-header">
        <div class="container container-backoffice">
          <h1 class="heading-1">
            <span class="text">Appointments</span>
            <button class="button main-action circle" @click="handleNewAppointmentClick">
              <svg><use xlink:href="#addition"/></svg>
            </button>
          </h1>
          <FilterButtons
            :active-filter="activeFilter"
            :filters="filters"
            :loading="disabledFilters"
            :on-filter="handleFilter"
            :all-data="$root.global.appointments"
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
        :context="flyoutMode"
        :has-card="appointment.patientPayment"
        :editable="editablePatient"
        :email="appointment.patientEmail"
        :is-visible="visiblePatient"
        :list="patientList"
        :name="patientDisplay"
        :phone="appointment.patientPhone"
        :set-patient="setPatientInfo"
      />

      <Practitioner
        :editable="editablePractitioner"
        :is-disabled="!appointment.patientId"
        :is-visible="visiblePractitioner"
        :name="appointment.practitionerName"
        :list="practitionerList"
        :set-practitioner="setPractitionerInfo"
      />

      <div class="input__container" v-if="appointment.patientAddress && flyoutMode === 'update' && userType !== 'patient'">
        <label class="input__label">Address</label>
        <p class="input__item" v-html="appointment.patientAddress"></p>
      </div>

      <div class="input__container" v-if="editableDays && flyoutMode === 'update'">
        <label class="input__label">Appointment</label>
        <span class="input__item">{{ confirmDate(appointment.currentDate) }}</span>
      </div>

      <Days
        :day="appointment.day"
        :editable="editableDays"
        :is-loading="loadingDays"
        :is-visible="visibleDays"
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

      <Duration
        :duration="appointment.duration.value"
        :editable="editableDuration"
        :is-visible="visibleDuration"
        :list="durationList"
        :set-duration="setDuration"
        data-test="duration"
      />

      <div class="input__container" v-if="appointment.googleMeet && appointment.currentStatus === 'pending'">
        <label class="input__label">Meet Link</label>
        <a :href="appointment.googleMeet" target="_blank">{{ appointment.googleMeet }}</a>
      </div>

      <Status
        :editable="editableStatus"
        :is-visible="visibleStatus"
        :list="statuses"
        :set-status="setStatus"
        :status="appointment.status"
      />

      <div class="input__container" v-if="appointment.currentStatus === 'complete'" data-test="section_billing">
        <label class="input__label">Billing Info</label>
        <div class="input__item">Duration: {{ appointment.currentDuration }}</div>
        <div class="input__item">Billed to: {{ billing.brand }} ****{{ billing.last4 }}</div>
        <div class="input__item" data-test="appointment_amount_charged">Charged: {{ appointment.duration.data === '60' ? '$150' : '$75' }}</div>
      </div>

      <Purpose
        :character-limit="purposeCharLimit"
        :editable="editablePurpose"
        :is-visible="visiblePurpose"
        :on-input="handlePurposeInput"
        :text-value="appointment.purpose"
      />

      <div class="input__container" v-if="visibleDiscount">
        <label class="input__label">Discount</label>
        <input v-model="discountCode" class="input--text" />
        <div class="copy-error" v-show="discountError">{{ discountError }}</div>
      </div>

      <p class="copy-error" v-show="showBillingError">Please save a credit card on file on the Settings page before booking an appointment.</p>
      <div class="button-wrapper">

        <button
          v-if="visibleNewButton"
          class="button"
          @click="handleConfirmationModal('new')"
          :disabled="disabledNewButton">Book Appointment</button>

        <button
          v-if="visibleUpdateButtons"
          class="button"
          @click="handleConfirmationModal('update')"
          data-test="button_update"
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
      :hide-close="isHandlingAction"
      data-test="modal_confirmation"
      :is-simple="isHandlingAction"
      :on-close="handleModalClose"
    >
      <div class="space-children-md" slot="content">
        <h2 class="heading-1 font-centered" v-show="!isHandlingAction">
          <span class="text">{{ userActionTitle }}</span>
        </h2>
        <div v-show="isHandlingAction" style="text-align: center; font-size: 22px;">
          <p>Updating appointments</p><br>
          <div style="width: 24px; margin: 0 auto;">
            <ClipLoader :size="'24px'" :color="'#4f6268'" />
          </div>
        </div>
        <div class="font-centered">
          <p v-show="bookingConflict && !isHandlingAction">We&rsquo;re sorry, it looks like that date and time is no longer available. Please try another time. For general questions, please give us a call at <a href="tel:8006909989">800-690-9989</a>, or talk with a representative by clicking the chat button at the bottom corner of the page.</p>
          <p v-show="!bookingConflict && !isHandlingAction && statusWasChanged && flyoutMode === 'update'">Are you sure you want to mark this appointment as {{ appointment.status | confirmStatus }}?</p>
        </div>
        <div class="space-children-sm" v-show="!bookingConflict && !isHandlingAction">
          <div class="Row-md" v-if="userType !== 'patient'">
            <div class="Column-md-1of5 space-bottom-xxs"><strong>Client:</strong></div>
            <div class="Column-md-4of5 font-thin">{{ appointment.patientName }}</div>
          </div>
          <div class="Row-md" v-if="userType !== 'practitioner'">
            <div class="Column-md-1of5 space-bottom-xxs"><strong>Doctor:</strong></div>
            <div class="Column-md-4of5 font-thin">{{ appointment.practitionerName }}</div>
          </div>
          <div class="Row-md">
            <div class="Column-md-1of5 space-bottom-xxs"><strong>Date/Time:</strong></div>
            <div class="Column-md-4of5 font-thin">{{ confirmDate(appointment.date) }}</div>
          </div>
          <div class="Row-md" v-if="flyoutMode === 'update'">
            <div class="Column-md-1of5 space-bottom-xxs"><strong>Status:</strong></div>
            <div class="Column-md-4of5 font-thin">{{ appointment.status | confirmStatus }}</div>
          </div>
          <div class="Row-md" v-if="appointment.status === 'complete'">
            <div class="Column-md-1of5 space-bottom-xxs"><strong>Duration:</strong></div>
            <div class="Column-md-4of5 font-thin">{{ appointment.duration.value }}</div>
          </div>
          <div class="Row-md" v-show="appointment.status === 'pending'">
            <div class="Column-md-1of5 space-bottom-xxs"><strong>Purpose:</strong></div>
            <div class="Column-md-4of5 font-thin">{{ appointment.purpose }}</div>
          </div>
          <div class="Row-md" v-show="appointment.status === 'canceled'">
            <div class="Column-md-1of5 space-bottom-xxs"><strong>Reason:</strong></div>
            <div class="Column-md-4of5 font-thin">
              <textarea v-model="cancellationReason"
                class="input--textarea"
                maxlength="1024"
                placeholder="Reason for cancelling appointment">
              </textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="font-centered" slot="footer" v-if="!isHandlingAction">
        <button class="Button--cancel" @click="handleModalClose" v-show="bookingConflict">Continue</button>
        <button class="Button--cancel" @click="handleModalClose" v-show="!bookingConflict">Cancel</button>
        <button class="Button" @click="handleConfirmationSubmission" v-show="!bookingConflict">Yes, Confirm</button>
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
import Duration from './components/Duration.vue';
import FilterButtons from '../../commons/FilterButtons.vue';
import Flyout from '../../commons/Flyout.vue';
import Modal from '../../v2/components/shared/Modal.vue';
import NotificationPopup from '../../commons/NotificationPopup.vue';
import Overlay from '../../commons/Overlay.vue';
import Patient from './components/Patient.vue';
import Practitioner from './components/Practitioner.vue';
import Purpose from './components/Purpose.vue';
import Status from './components/Status.vue';
import Times from './components/Times.vue';

// ClipLoader must be imported from the src and the .vue extension
// must be included or Karma yells at your
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
import convertStatus from './utils/convertStatus';
import moment from 'moment';
// import tableColumns from './utils/tableColumns';
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
      billingConfirmed: Laravel.user.has_a_card,
      billing: {
        brand: Laravel.user.card_brand,
        last4: Laravel.user.card_last4
      },
      bookingConflict: false,
      cache: {
        upcoming: [],
        past: [],
        cancelled: []
      },
      cancellationReason: '',
      discountCode: '',
      discountError: '',
      durationList: [
        { data: 30, value: '30 minutes' },
        { data: 60, value: '60 minutes' }
      ],
      filters: ['Upcoming', 'Complete', 'Cancelled'],
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
      showBillingError: false,
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
    };
  },

  name: 'appointments',

  components: {
    AppointmentTable,
    ClipLoader,
    Days,
    Duration,
    FilterButtons,
    Flyout,
    Modal,
    NotificationPopup,
    Overlay,
    Patient,
    Practitioner,
    Purpose,
    Status,
    Times
  },

  filters: {
    confirmStatus(status) {
      return convertStatus(status);
    }
  },

  computed: {
    disabledFilters() {
      return this.$root.$data.global.loadingAppointments || this.selectedRowUpdating !== null;
    },
    disabledNewButton() {
      return this.flyoutMode === 'new' &&
        // If no date was set or no patient was selected by admin/practitioner
        (!this.appointment.date || (!this.appointment.patientId && this.userType !== 'patient'));
    },
    disableUpdateButton() {
      return this.flyoutMode === 'update' &&
        // If status is being marked complete but duration has not yet been selected
        (this.appointment.status === 'complete' && !this.appointment.duration.value) ||
        // or if none of information has actually been updated
        (
          (this.appointment.date === '' || this.appointment.date === this.appointment.currentDate) &&
          (this.appointment.purpose === this.appointment.currentPurpose) &&
          (this.appointment.status === this.appointment.currentStatus)
        );

    },
    editableDays() {
      if (this.flyoutMode === 'new') return true;
      if (this.userType === 'patient' && this.appointment.status !== 'pending') return false;
      if (this.appointment.status !== 'pending') return false;
      return this.checkPastAppointment();
    },
    editableDuration() {
      return this.userType !== 'patient' && !this.appointment.currentDuration;
    },
    editableStatus() {
      return this.userType !== 'patient' && this.appointment.currentStatus === 'pending';
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
      if (this.appointment.status !== 'pending') return false;
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
    statusWasChanged() {
      return this.appointment.status !== this.appointment.currentStatus;
    },
    visibleDays() {
      return this.flyoutMode === 'update' ||
        (this.userType === 'practitioner' && this.appointment.patientName !== '') ||
        (this.userType !== 'practitioner' && this.appointment.practitionerName !== '');
    },
    visibleDiscount() {
      return this.flyoutMode === 'new' && App.Config.user.isPatient && this.appointment.date;
    },
    visibleDuration() {
      return this.appointment.status === 'complete' && this.appointment.currentStatus !== 'complete';
    },
    visibleNewButton() {
      return this.flyoutMode === 'new' && (this.appointment.patientPayment !== false || this.userType === 'admin');
    },
    visibleStatus() {
      return this.flyoutMode !== 'new';
    },
    visiblePatient() {
      return this.userType !== 'patient';
    },
    visiblePractitioner() {
      return this.userType !== 'practitioner' && (this.flyoutMode === 'update' || this.userType === 'patient' || this.appointment.patientName !== '');
    },
    visiblePurpose() {
      return this.flyoutMode === 'update' || this.appointment.date !== '';
    },
    visibleUpdateButtons() {
      return this.flyoutMode === 'update' &&
        (this.userType !== 'patient' && this.appointment.currentStatus === 'pending') ||
        (this.userType === 'patient' && this.checkPastAppointment()) &&
        (this.userType === 'patient' && this.appointment.status === 'pending');
    }
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

    confirmDate(date) {
      return `${toLocal(date, 'dddd, MMMM Do [at] h:mm a')} (${moment.tz(moment.tz.guess()).format('z')})`;
    },

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
      }).catch(error => {

      });
    },

    // When user clicks the CTA in the flyout for updating, canceling, or creating an appointment.
    // This is the initial setup before the api call is actually made.
    handleConfirmationModal(action) {

      this.userAction = action;
      this.appointment.purpose = this.appointment.purpose || 'New appointment';
      switch (action) {
        case 'cancel':
          this.userActionTitle = 'Cancel Appointment';
          this.appointment.status = 'canceled';
          this.appointment.date = this.appointment.currentDate;
          break;
        case 'update':
          switch (this.appointment.status) {
            case 'pending':
              this.userActionTitle = 'Update Appointment';
              break;
            case 'complete':
              this.userActionTitle = 'Complete Appointment';
              break;
            default:
              this.userActionTitle = 'Cancel Appointment';
              break;
          }
          if (this.appointment.status !== 'pending' || this.appointment.date === '') {
            this.appointment.date = this.appointment.currentDate;
          }
          break;
        case 'new':
          if (!this.billingConfirmed && this.userType === 'patient') {
            this.showBillingError = true;
            return;
          }
          if (this.$root.shouldTrack()) {
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
      this.handleRowClick();
      switch(name) {
        case 'Upcoming':
          this.appointments = this.cache.upcoming;
          break;
        case 'Complete':
          this.appointments = this.cache.past;
          break;
        case 'Cancelled':
          this.appointments = this.cache.cancelled;
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

      // The Practitioner dropdown is disabled if there is no patientId listed so that admins
      // or practitioners cannot select a doctor prior to selecting a patient (for licensing regulations)
      // Since on new appointments we do not have an associated patientId, we set it as true here
      if (this.userType === 'patient') {
        this.appointment.patientId = true;
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
      this.appointment.currentDuration = '';
      this.appointment.duration = { data: '', value: '' };

      if (data) {
        this.selectedRowData = data;
        this.selectedRowIndex = index;
        // appointment id
        this.appointment.id = data._appointmentId;

        // patient info
        this.appointment.patientEmail = data._patientEmail;
        this.appointment.patientName = `${data._patientLast}, ${data._patientFirst}`;
        this.appointment.patientPhone = data._patientPhone;
        this.appointment.patientPayment = data._hasCard;
        if (this.userType !== 'patient') this.appointment.patientId = data._patientId;
        this.patientDisplay = this.appointment.patientName;

        // patient address
        this.appointment.patientAddress = this.setPatientAddress(data);
        this.appointment.patientState = data.state;

        // If user is admin, filter practitioners by state licensing regulations
        // First reset the practitioner list
        this.setupPractitionerList(this.$root.$data.global.practitioners);

        // store current date
        this.appointment.currentDate = moment(data._date).format('YYYY-MM-DD HH:mm:ss');
        this.appointment.currentPurpose = data.purpose;

        // Google Meet
        this.appointment.googleMeet = data._google_meet_link;

        // set status
        this.appointment.currentStatus = convertStatus(data.status);
        this.appointment.status = convertStatus(data.status);

        // set duration
        if (data.status === 'Complete' && data._duration) {
          this.appointment.currentDuration = `${data._duration} minutes`;
          this.appointment.duration = {
            data: data._duration,
            value: this.appointment.currentDuration
          };
        }

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

    handleConfirmationSubmission() {
      this.discountError = '';
      if (this.discountCode) {
        const endpoint = `${this.$root.$data.apiUrl}/discountcode?discount_code=${this.discountCode}&applies_to=consultation`;
        axios.get(endpoint).then(response => {
          if (response.data.errors) {
            this.discountError = 'Invalid discount code.';
          } else {
            this.handleUserAction(this.discountCode);
          }
        }).catch(error => {
          if (error.response) {
            console.warn(error.response);
            // document.body.innerHTML = error.response.data;
          };
        })
      } else {
        this.handleUserAction();
      }
    },

    handleUserAction(discount) {
      // Setup
      let data = {
        appointment_at: this.appointment.date || this.appointment.currentDate,
        reason_for_visit: this.appointment.purpose,
        status: this.appointment.status,
        patient_id: this.appointment.patientId * 1,
        practitioner_id: this.appointment.practitionerId * 1
      };

      //
      if (discount) data.discount_code = discount;

      let action = this.userAction === 'new' ? 'post' : 'patch';
      let endpoint = this.userAction === 'new' ? '/api/v1/appointments' : `/api/v1/appointments/${this.appointment.id}`;
      const succesPopup = this.userAction !== 'cancel';
      this.notificationMessage = this.userAction === 'new' ? 'Appointment Created!' : 'Appointment Updated!';

      // collect data for tracking later
      const appointmentStatus = this.appointment.status;
      const appointmentDate = data.appointment_at;
      const appointmentPatientEmail = this.appointment.patientEmail;

      // api constraints
      const isPatient = this.userType === 'patient';
      const isPractitioner = this.userType === 'practitioner';
      const isAdmin = this.userType === 'admin';
      const isUpdate = this.userAction === 'update';
      const isCancel = this.userAction === 'cancel';
      const isNew = this.userAction === 'new';
      const hasDoctorSwitch = isUpdate && (this.appointment.currentPractitionerId !== this.appointment.practitionerId);
      const hasTimeSwitch = this.appointment.currentDate !== this.appointment.date;
      const adminSwitchesDoctor = isAdmin && hasDoctorSwitch;

      // Patients don't need to send up their id
      // Cancellations don't require a patient id
      // Patient id is required if new appointment made by admin or practitioner
      // Patient id only required on update if admin switched doctors
      const shouldKeepPatient = !isPatient && !isCancel && (isNew || adminSwitchesDoctor);

      // Practitioner id is required for new appointment creations
      // Practitioner id is only required in an update if an admin is switching a doctor
      const shouldKeepPractitioner = isNew || (isUpdate && adminSwitchesDoctor);

      // Time is not necessary when an admin or practitioner updates appointment status or purpose and not Time
      // Time should remain if an admin changes a doctor for an existing appointment
      // Time is not necessary for appointment cancellations
      const shouldKeepTime = hasTimeSwitch || hasDoctorSwitch;

      if (!shouldKeepPatient) delete data.patient_id;
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
        endpoint = '/api/v1/appointments';
      }
      if (this.appointment.status === 'complete') {
        data.duration_in_minutes = this.appointment.duration.data;
      }

      // Reset appointment here so that subsequent row clicks don't get reset after api call
      const apptId = this.appointment.id;
      const oldIds = this.appointments.map(appt => appt.data._appointmentId);
      this.appointment = this.resetAppointment();
      this.isHandlingAction = true;

      // If user is patient and action is cancel, add cancellation_reason to data
      if (isPatient && isCancel && this.cancellationReason) {
        data.cancellation_reason = this.cancellationReason;
      }

      // Make the call
      // TO-DO: Add error notifications if api call fails
      axios[action](endpoint, data).then((response) => {
        // track the event
        if(this.$root.shouldTrack()) {
          if((isPractitioner || isAdmin) && appointmentStatus === 'complete') {
            analytics.track('Consultation Completed', {
              date: appointmentDate,
              email: appointmentPatientEmail
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
            } else if (!isNew) {
              this.selectedRowHasUpdated = newIds.indexOf(apptId);
            }
            setTimeout(() => this.selectedRowHasUpdated = null, 2000);
          });
        });
      }).catch(error => {
        if (error.response) console.error(error.response);
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
        duration: { data: '', value: ''},
        cancellationReason: '',
        currentDate: '',
        currentDuration: '',
        currentPractitionerId: '',
        currentPurpose: '',
        currentStatus: '',
        id: '',
        googleMeet: '',
        status: '',
        patientAddress: '',
        patientPayment: null,
        patientEmail: '',
        patientId: '',
        patientName: '',
        patientPhone: '',
        patientState: '',
        practitionerAvailability: [],
        practitionerId: '',
        practitionerName: '',
        purpose: '',
        time: ''
      };
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

    setDuration(obj) {
      this.appointment.duration = obj;
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
      this.appointment.patientPayment = data.has_a_card;
      this.appointment.patientEmail = data.email;
      this.appointment.patientName = data.name;
      this.appointment.patientId = data.id;
      this.appointment.patientPhone = data.phone;
      this.patientDisplay = data.name;

      // Reset dependent inputs
      this.appointment.practitionerId = '';
      this.appointment.practitionerName = '';
      this.appointment.practitionerAvailability = [];
      this.appointment.availableTimes = [];
      this.appointment.date = '';
      this.appointment.date = '';
      this.appointment.time = '';

      // If user is admin, filter practitioners by state licensing regulations
      // First reset the practitioner list
      Vue.nextTick(() => {
        this.setupPractitionerList(this.$root.$data.global.practitioners);
        this.practitionerList = this.$root.filterPractitioners(this.practitionerList, data.state);
      });
    },

    // Set practitioner info with data from list object
    setPractitionerInfo(data) {
      this.appointment.practitionerId = data.id;
      this.appointment.practitionerName = data.name;
      this.getAvailability(this.appointment.practitionerId);
    },

    setStatus(status) {
      this.appointment.status = status.data;
      if (status.data !== 'pending') {
        this.appointment.date = '';
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
    },

    setupAppointments(list) {
      const zone = this.$root.addTimezone();
      const appts = tableDataTransform(list, zone, this.userType).sort(tableSort.byDate('_date')).reverse();

      this.cache.upcoming = appts.filter(obj => moment(obj.data._date).diff(moment()) > 0 && obj.data.status === 'Pending');
      this.cache.past = appts.filter(obj => moment(obj.data._date).diff(moment()) < 0 && obj.data.status === 'Pending' || obj.data.status === 'Complete');
      this.cache.cancelled = appts.filter(obj => obj.data.status !== 'Pending' && obj.data.status !== 'Complete');

      this.appointments = this.activeFilter === 0
        ? this.cache.upcoming
        : this.activeFilter === 1
          ? this.cache.past
          : this.cache.cancelled;

      if (this.appt_id) {
        let appt, index;
        this.appointments.forEach((obj, i) => {
          if (obj.data._appointmentId === this.appt_id) {
            index = i;
            appt = obj;
          }
        });
        this.handleRowClick(appt, index);
      }

      Vue.nextTick(() => {
        this.checkTableData();
      });
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
      if (this.userType === 'practitioner') {
        this.setPractitionerInfo(this.practitionerList[0].data);
      }
      if (this.userType === 'admin' && this.appointment.patientState) {
        this.practitionerList = this.$root.filterPractitioners(this.practitionerList, this.appointment.patientState);
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
};
</script>
