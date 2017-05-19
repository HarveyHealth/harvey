<template>
  <div class="main-container">

    <UserNav />

    <div class="main-content">
      <div class="main-header">
        <div class="container">
          <h1 class="title header-xlarge">
            Appointments
            <button
              v-show="(userType === 'admin' && patientDataCollected) || userType === 'patient'"
              href="#"
              class="button main-action"
              @click.prevent="newAppointmentSetup()"
            >
              New Appointment
            </button>
          </h1>
        </div>
      </div>

      <TableData :allTableData="tableData" :config="tableConfig"/>

    </div>

    <Flyout>
      <PatientInput
        :patientlist="patientList"
        :type="appointmentModType"
        :usertype="userType"
      />
      <DoctorName
        :doctorid="appointmentData.doctorId"
        :doctorname="appointmentData.doctorName"
        :doctorlist="doctorList"
        :type="appointmentModType"
        :usertype="userType"
      />
      <DayAndTime
        :availability="doctorAvailability"
        :date="appointmentData.appointmentDate"
        :past="appointmentData.pastAppointment"
        :type="appointmentModType"
      />
      <Status
        :past="appointmentData.pastAppointment"
        :statuses="statuses"
        :type="appointmentModType"
        :usertype="userType"
      />
      <PurposeInput
        :past="appointmentData.pastAppointment"
        :purposetext="appointmentData.appointmentPurpose"
        :type="appointmentModType"
        :usertype="userType"
      />
      <div class="inline-centered">
        <button
          v-if="appointmentModType === 'new'"
          @click.prevent="setupAppointmentNew()"
          class="button"
          :disabled="!noAvailability"
        >Book Appointment</button>
        <button
          v-if="appointmentModType === 'update' && !appointmentData.pastAppointment"
          @click.prevent="setupAppointmentUpdate()"
          class="button"
        >Update Appointment</button>
        <a href="#"
          class="input__linkcta"
          v-if="appointmentModType === 'update' && !appointmentData.pastAppointment"
          @click.prevent="setupAppointmentCancel()"
        >Cancel Appointment</a>
      </div>
    </Flyout>

    <Overlay />

    <AppointmentModal
      :affirm="confirmationButton"
      :affirmEvent="confirmationEvent"
      :text="confirmationText"
      :title="confirmationTitle" />

  </div>
</template>

<script>
  // Components
  import AppointmentModal from './components/AppointmentModal.vue';
  import DayAndTime from './components/DayAndTime.vue';
  import DoctorName from './components/DoctorName.vue';
  import Flyout from '../../commons/Flyout.vue';
  import Overlay from '../../commons/Overlay.vue';
  import PatientInput from './components/PatientInput.vue';
  import PurposeInput from './components/PurposeInput.vue';
  import Status from './components/Status.vue';
  import TableData from './components/TableData.vue';
  import UserNav from '../../commons/UserNav.vue';

  // Helpers
  import { capitalize, phone, hyperlink } from '../../utils/filters/textformat.js';
  import Contact from '../../utils/mixins/Contact';
  import combineAppointmentData from './utils/combineAppointmentData.js';
  import moment from 'moment-timezone';
  import tableConfig from './utils/tableconfig';
  import toLocalTimezone from '../../utils/methods/toLocalTimezone';

  export default {
    name: 'appointments',
    props: ['user', 'patient'],
    components: {
      AppointmentModal,
      DayAndTime,
      DoctorName,
      Flyout,
      Overlay,
      PatientInput,
      PurposeInput,
      Status,
      TableData,
      UserNav,
    },
    data() {
      return {
        _appointmentDetails: [],
        appointmentData: {
          appointmentId: '',
          appointmentDate: '',
          appointmentDay: '',
          appointmentPurpose: '',
          appointmentStatus: '',
          appointmentTime: '',
          doctorId: '',
          doctorName: '',
          pastAppointment: '',
          patientName: '',
          patientEmail: '',
          patientPhone: ''
        },
        appointmentModType: null,
        apiParameters: 'include=patient.user',
        confirmationButton: '',
        confirmationEvent: '',
        confirmationText: '',
        confirmationTitle: '',
        dataForCancel: {
          id: null,
        },
        dataForNew: {
          appointment_at: null,
          patient_id: null,
          practitioner_id: null,
          reason_for_visit: null,
          status: null,
        },
        dataForUpdate: {
          appointment_at: null,
          id: null,
          patient_id: null,
          practitioner_id: null,
          reason_for_visit: null,
          status: null
        },
        dataCollected: false,
        doctorAvailability: {},
        doctorList: [],
        patientDataCollected: false,
        patientList: [],
        statuses: {
          'pending': 'Pending',
          'no_show_patient': 'No-Show-Patient',
          'no_show_doctor': 'No-Show-Doctor',
          'general_conflict': 'General Conflict',
          'canceled': 'Canceled',
          'complete': 'Complete'
        },
        tableConfig: tableConfig,
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
      noAvailability() {
        if (this.doctorAvailability.length) {
          return this.doctorAvailability[0].concat(this.doctorAvailability[1]).length;
        } else {
          return false;
        }
      },
      selectedTableData() {
        return this.appointmentData;
      },
      tableData() {
        return this.dataCollected
          ? this.appointmentTablePrep(this.appointmentDetails)
          : [];
      }
    },
    watch: {
      _appointmentDetails() {
        this.getAppointmentData();
      }
    },
    methods: {
      // The TableData component consumes data in a particular format. This
      // just takes the data returned from the api and puts it in the proper format.
      // See docs in _components/TableData.vue
      appointmentTablePrep(appointmentData) {
        return appointmentData.map(appt => {
          return {
            formatted: {
              'Date': {
                'value': moment(appt.attributes.appointment_at.date).format('ddd MMM Do'),
                'width': '15%' },
              'Time': {
                'value': moment(appt.attributes.appointment_at.date).format('h:mm a'),
                'width': '10%' },
              'Client': {
                'value': `${capitalize(appt.patientData.last_name)}, ${capitalize(appt.patientData.first_name)}`,
                'width': '15%' },
              'Doctor': {
                'value': `Dr. ${appt.attributes.practitioner_name}`,
                'width': '15%' },
              'Status': {
                'value': capitalize(appt.attributes.status),
                'width': '10%' },
              'Purpose': {
                'value': appt.attributes.reason_for_visit,
                'width': '35%' },
            },
            raw: appt
          }
        });
      },
      // The same components are used in the flyout for updating appointments and creating new appointments.
      // This function resets all the data values to an empty string, switches the mod type to 'new' and then
      // fires all the events to initiate the flyout for appointment creation.
      newAppointmentSetup() {
        Object.keys(this.appointmentData).forEach(key => this.appointmentData[key] = '');
        this.appointmentModType = 'new';
        Vue.nextTick(() => {
          if (this.userType !== 'patient') {
            this.$eventHub.$emit('setPatient', this.patientList[0].id);
            this.appointmentData.patientName = this.patientList[0].name;
            this.dataForNew.patient_id = this.patientList[0].id;
          }
            this.appointmentData.doctorName = this.doctorList[0].name;
            this.appointmentData.doctorId = this.doctorList[0].id;
            this.dataForNew.practitioner_id = this.doctorList[0].id;

          this.dataForNew.status = 'pending';
          this.dataForNew.reason_for_visit = '';

          this.$eventHub.$emit('setPurposeText');
          this.$eventHub.$emit('setStatus', 'pending');
          this.$eventHub.$emit('getDoctorAvailability', this.doctorList[0].id);
          this.$eventHub.$emit('toggleOverlay');
          this.$eventHub.$emit('deselectRows');
          this.$eventHub.$emit('callFlyout', false);
        })
      },
      resetAppointmentData(data) {
        let output = data;
        for (let appt in output) {
          output[appt] = '';
        }
        return output;
      },
      setupAppointmentCancel() {
        this.confirmationButton = 'Yes, Cancel Appointment';
        this.confirmationEvent = 'cancelAppointment';
        this.confirmationTitle = 'Confirm Appointment Cancellation';

        this.confirmationText = {};
        if (this.userType !== 'patient') this.confirmationText.Client = this.appointmentData.patientName;
        if (this.userType !== 'practitioner') this.confirmationText.Doctor = this.appointmentData.doctorName;
        this.confirmationText['Booked For'] = moment(this.dataForUpdate.appointment_at).format('dddd, MMMM Do [at] h:mm a');
        this.confirmationText['Status'] = this.statuses[this.dataForUpdate.status];
        this.confirmationText['Purpose'] = this.dataForUpdate.reason_for_visit;

        this.$eventHub.$emit('callAppointmentModal');
      },
      setupAppointmentNew() {
        this.confirmationButton = 'Yes, Book Appointment';
        this.confirmationEvent = 'bookAppointment';
        this.confirmationTitle = 'Confirm Appointment Booking';

        this.confirmationText = {};
        if (this.userType !== 'patient') this.confirmationText.Client = this.appointmentData.patientName;
        if (this.userType !== 'practitioner') this.confirmationText.Doctor = this.appointmentData.doctorName;
        this.confirmationText['Booked For'] = moment(this.dataForNew.appointment_at).format('dddd, MMMM Do [at] h:mm a');
        this.confirmationText['Purpose'] = this.dataForNew.reason_for_visit;

        this.$eventHub.$emit('callAppointmentModal');
      },
      setupAppointmentUpdate() {
        this.confirmationButton = 'Yes, Update Appointment';
        this.confirmationEvent = 'updateAppointment';
        this.confirmationTitle = 'Confirm Appointment Update';

        this.confirmationText = {};
        if (this.userType !== 'patient') this.confirmationText.Client = this.appointmentData.patientName;
        if (this.userType !== 'practitioner') this.confirmationText.Doctor = this.appointmentData.doctorName;
        this.confirmationText['Booked For'] = moment(this.dataForUpdate.appointment_at).format('dddd, MMMM Do [at] h:mm a');
        this.confirmationText['Status'] = this.statuses[this.dataForUpdate.status];
        this.confirmationText['Purpose'] = this.dataForUpdate.reason_for_visit;

        this.$eventHub.$emit('callAppointmentModal');
      },
      getAppointmentData() {
        // For right now, we're just adding all appointments. Future iterations will include filters
        // for upcoming and recent appointments
        axios.get(`${this.$root.apiUrl}/appointments?${this.apiParameters}`).then(response => {
          this._appointmentDetails = combineAppointmentData(response.data).reverse();
          this.dataCollected = true;
        })
      },
      toLocalTimezone
    },
    filters: {
      formatPhone(num) {
        return phone(num);
      }
    },
    // We're making a whole lot of calls here for basic information. Perhaps when we get global state
    // management in a better spot, we can get role-specific data sets up front so this information
    // is just always available.
    created() {
      this.getAppointmentData();

      if (this.userType !== 'practitioner') {
        axios.get(`${this.$root.apiUrl}/practitioners?include=availability`).then(response => {
          this.doctorList = response.data.data.map(dr => {
            return { name: `Dr. ${dr.attributes.name}`, id: dr.id }
          })
        })
      } else {
        axios.get(`${this.$root.apiUrl}/practitioners?include=availability`).then(response => {
          this.doctorList = response.data.data.filter(dr => {
            return dr.attributes.name === Laravel.user.fullName;
          }).map(obj => {
            return { name: `Dr. ${obj.attributes.name}`, id: obj.id };
          })
        })
      }

      if (this.userType !== 'patient') {
        axios.get(`${this.$root.apiUrl}/patients?include=user`).then(response => {
          const include = response.data.included;
          response.data.data.forEach((obj, i) => {
            this.patientList.push({
              id: obj.id,
              name: `${include[i].attributes.last_name}, ${include[i].attributes.first_name}`,
              email: include[i].attributes.email,
              phone: include[i].attributes.phone
            })
          });
          // Sort by last name
          this.patientList = this.sortByLastName(this.patientList);
          this.patientDataCollected = true;
        })
      }
    },
    mounted() {
      // Clicking the overlay disengages the flyout
      this.$eventHub.$on('overlayClicked', () => {
        this.$eventHub.$emit('callFlyout', true);
        this.$eventHub.$emit('toggleOverlay');
        setTimeout(() => this.doctorAvailability = [], 400);
      })

      // TableData emits rowClickEvent when a row is selected. It takes the data associated with
      // that row and passes it along in the event along with whether or not the row is currently
      // active. This helps for any toggle events you may need to trigger.
      this.$eventHub.$on('rowClickEvent', (rowData, rowIsActive) => {
        this.appointmentModType = 'update';

        // Refactored logic
        // Set flyout component data
        this.$eventHub.$emit('setStatus', rowData.attributes.status);
        this.dataForUpdate.status = rowData.attributes.status;
        if (this.userType !== 'patient') {
          this.$eventHub.$emit('setPatient', rowData.attributes.patient_id);
        }
        // Set initial data for CRUD operations
        this.dataForUpdate.appointment_at = moment(rowData.attributes.appointment_at.date).format('YYYY-MM-DD HH:mm:ss');
        this.dataForUpdate.id = rowData.id;
        this.dataForUpdate.patient_id = rowData.attributes.patient_id;
        this.dataForUpdate.practitioner_id = rowData.attributes.practitioner_id;
        this.dataForUpdate.reason_for_visit = rowData.attributes.reason_for_visit;
        this.dataForUpdate.status = rowData.attributes.status;
        this.dataForCancel.id = rowData.id;

        // Old stuff we may not use
        this.appointmentData = this.resetAppointmentData(this.appointmentData);
        if (!rowIsActive) {
          this.appointmentData = {
            appointmentDate: rowData.attributes.appointment_at.date,
            appointmentDay: moment(rowData.attributes.appointment_at.date).format('ddd MMM Do'),
            appointmentPurpose: rowData.attributes.reason_for_visit,
            appointmentStatus: rowData.attributes.status,
            appointmentTime: moment(rowData.attributes.appointment_at.date).format('h:mm a'),
            doctorId: rowData.attributes.practitioner_id,
            doctorName: `Dr. ${rowData.attributes.practitioner_name}`,
            pastAppointment: moment().diff(moment(rowData.attributes.appointment_at.date)) > 0,
            patientEmail: rowData.patientData.email,
            patientName: `${capitalize(rowData.patientData.last_name)}, ${capitalize(rowData.patientData.first_name)}`,
            patientPhone: rowData.patientData.phone
          }
        }

        // console.log(rowData);

        this.$eventHub.$emit('callFlyout', rowIsActive);
        // PurposeInput uses a v-model to calculate character count. In order to populate the
        // textarea, we need to call an event on the next tick after data has been defined
        // from the row click.
        Vue.nextTick(() => {
          this.$eventHub.$emit('setPurposeText');

          if (!rowIsActive) {
            this.$eventHub.$emit('getDoctorAvailability', rowData.attributes.practitioner_id);
            this.$eventHub.$emit('populateAvailableTimes');
          } else {
            this.doctorAvailability = [];
          }
        });
      });
      // The Appointments component holds the doctorAvailability data. When a doctor
      // is selected from the dropdown, it is updated with this event after the api call
      // response data comes back
      this.$eventHub.$on('returnAvailability', response => {
        this.doctorAvailability = response.meta.availability;
      })

      this.$eventHub.$on('updatePatient', (id, name) => {
        this.dataForNew.patient_id = id;
        this.appointmentData.patientName = name;
      })

      this.$eventHub.$on('updateDoctor', id => {
        this.dataForUpdate.practitioner_id = id;
        this.dataForNew.practitioner_id = id;
      })

      this.$eventHub.$on('updateDayTime', timeObj => {
        this.dataForUpdate.appointment_at = this.toLocalTimezone(timeObj, this.$root.timezone).format('YYYY-MM-DD HH:mm:ss');
        this.dataForNew.appointment_at = this.toLocalTimezone(timeObj, this.$root.timezone).format('YYYY-MM-DD HH:mm:ss');
      })

      this.$eventHub.$on('updateStatus', value => {
        this.dataForUpdate.status = value.replace(/-| /g, '_').toLowerCase();
        this.appointmentData.appointmentStatus = value;
      })

      this.$eventHub.$on('updatePurpose', purposeText => {
        this.dataForUpdate.reason_for_visit = purposeText;
        this.dataForNew.reason_for_visit = purposeText;
      })

      this.$eventHub.$on('bookAppointment', () => {
        const data = {
          appointment_at: moment(this.dataForNew.appointment_at).utc().format('YYYY-MM-DD hh:mm:ss'),
          reason_for_visit: this.dataForNew.reason_for_visit || 'No reason given.',
          practitioner_id: this.dataForNew.practitioner_id * 1,
        }

        if (this.userType !== 'patient') data.patient_id = this.dataForNew.patient_id * 1;

        axios.post('/api/v1/appointments', data).then(response => {
          this.$eventHub.$emit('refreshTable');
        }).catch(err => console.error(err.response));
        this.$eventHub.$emit('callFlyout', true);
        this.$eventHub.$emit('toggleOverlay');


      })

      this.$eventHub.$on('cancelAppointment', () => {
        axios.delete(`/api/v1/appointments/${this.dataForCancel.id}`).then(response => {
          this.$eventHub.$emit('refreshTable');
        }).catch(err => console.error(err.response));
        this.$eventHub.$emit('callFlyout', true);
        this.$eventHub.$emit('deselectRows');
      })

      this.$eventHub.$on('updateAppointment', () => {
        axios.patch(`/api/v1/appointments/${this.dataForUpdate.id}`, {
          appointment_at: this.dataForUpdate.appointment_at,
          reason_for_visit: this.dataForUpdate.reason_for_visit || 'No reason given.',
          status: this.dataForUpdate.status,
        }).then(response => {
          this.$eventHub.$emit('refreshTable');
        }).catch(err => console.error(err.response));
        this.$eventHub.$emit('callFlyout', true);
        this.$eventHub.$emit('deselectRows');
      })

    },
    destroyed() {
      this.$eventHub.$off('overlayClicked');
      this.$eventHub.$off('rowClickEvent');
      this.$eventHub.$off('returnAvailability');
      this.$eventHub.$off('updatePatient');
      this.$eventHub.$off('updateDoctor');
      this.$eventHub.$off('updateDayTime');
      this.$eventHub.$off('updateStatus');
      this.$eventHub.$off('updatePurpose');
      this.$eventHub.$off('bookAppointment');
      this.$eventHub.$off('cancelAppointment');
      this.$eventHub.$off('updateAppointment');
    }
  }
</script>
