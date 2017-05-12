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
            <button href="/schedule" class="button main-action" @click="newAppointmentSetup()">New Appointment</button>
          </h1>
        </div>
      </div>
      <TableData :allTableData="tableData" />
    </div>

    <Flyout>
      <PatientInput
        :type="appointmentModType"
        :usertype="userType"
        :patientname="appointmentData.patientName"
        v-model="appointmentData.patientName"
      >
        <div><a :href="'mailto:' + appointmentData.patientEmail">{{ appointmentData.patientEmail }}</a></div>
        <div><a :href="'tel:' + appointmentData.patientPhone">{{ appointmentData.patientPhone | formatPhone }}</a></div>
      </PatientInput>
      <!-- doctorlist needs api call? -->
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
      <PurposeInput
        :past="appointmentData.pastAppointment"
        :purposetext="appointmentData.appointmentPurpose"
        :type="appointmentModType"
        :usertype="userType"
      />
    </Flyout>

    <Overlay />

  </div>
</template>

<script>
  // Components
  import DayAndTime from '../_components/DayAndTime.vue';
  import DoctorName from '../_components/DoctorName.vue';
  import Flyout from '../_components/Flyout.vue';
  import Overlay from '../_components/Overlay.vue';
  import PatientInput from '../_components/PatientInput.vue';
  import PurposeInput from '../_components/PurposeInput.vue';
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
      DayAndTime,
      DoctorName,
      Flyout,
      Overlay,
      PatientInput,
      PurposeInput,
      TableData
    },
    data() {
      return {
        _appointmentDetails: [],
        appointmentData: {
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
        dataCollected: false,
        doctorAvailability: {},
        // TO-DO: Tate is working on getting this api endpoint for me
        doctorList: [],
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
        return this.appointmentData;
      },
      tableData() {
        return this.dataCollected
          ? this.appointmentTablePrep(this.appointmentDetails)
          : [];
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
          this.$eventHub.$emit('setPurposeText');
          this.$eventHub.$emit('getDoctorAvailability', this.doctorList[0].id);
          this.$eventHub.$emit('toggleOverlay');
          this.$eventHub.$emit('deselectRows');
          this.$eventHub.$emit('callFlyout', false);
        })
      }
    },
    filters: {
      formatPhone(num) {
        return phone(num);
      }
    },
    created() {
      // For right now, we're just adding all appointments. Future iterations will include filters
      // for upcoming and recent appointments
      axios.get(`${this.$root.apiUrl}/appointments?${this.apiParameters}`).then(response => {
        this._appointmentDetails = combineAppointmentDetails(response.data).reverse();
        this.dataCollected = true;
        // If the user is the practitioner, the doctorList should just include the practitioner
        // To avoid making another call for the practitioner_id, we're just using the appointments list
        if (this.userType === 'practitioner') {
          this.doctorList = [{
              name: this._appointmentDetails[0].attributes.practitioner_name,
              id: this._appointmentDetails[0].attributes.practitioner_id
          }]
        }
      })
      // This is where we'll get the practitioner list... eventually. Pretend there's a call.
      // The call will only be made if the user is not a practitioner because a doctor shouldn't
      // be allowed to schedule new appointments for other doctors.
      if (this.userType !== 'practitioner') {
        this.doctorList = [{name:'Dr. Twila Block', id: 35}, {name:'Dr. Cierra Grady',id:1}, {name:'Dr. Maria Jacobi', id:26}];
      }
    },
    mounted() {
      // Clicking the overlay disengages the flyout
      this.$eventHub.$on('overlayClicked', () => {
        this.$eventHub.$emit('callFlyout', true);
        this.$eventHub.$emit('toggleOverlay');
      })

      // TableData emits rowClickEvent when a row is selected. It takes the data associated with
      // that row and passes it along in the event along with whether or not the row is currently
      // active. This helps for any toggle events you may need to trigger.
      this.$eventHub.$on('rowClickEvent', (rowData, rowIsActive) => {
        this.appointmentModType = 'update';
        this.appointmentData = {
          appointmentDate: rowData.attributes.appointment_at.date,
          appointmentDay: moment(rowData.attributes.appointment_at.date).format('ddd MMM Do'),
          appointmentPurpose: rowData.attributes.reason_for_visit,
          appointmentStatus: 'Pending', // Still need this from api?
          appointmentTime: moment(rowData.attributes.appointment_at.date).format('h:mm a'),
          doctorId: rowData.attributes.practitioner_id,
          doctorName: `Dr. ${rowData.attributes.practitioner_name}`,
          pastAppointment: moment().diff(moment(rowData.attributes.appointment_at.date)) > 0,
          patientEmail: rowData.patientData.email,
          patientName: `${capitalize(rowData.patientData.first_name)} ${capitalize(rowData.patientData.last_name)}`,
          patientPhone: rowData.patientData.phone
        }
        this.$eventHub.$emit('callFlyout', rowIsActive);
        // PurposeInput uses a v-model to calculate character count. In order to populate the
        // textarea, we need to call an event on the next tick after data has been defined
        // from the row click.
        Vue.nextTick(() => {
          this.$eventHub.$emit('setPurposeText');
          if (!rowIsActive) {
            this.$eventHub.$emit('getDoctorAvailability');
            this.$eventHub.$emit('populateAvailableTimes');
          }
        });
      });

      this.$eventHub.$on('returnAvailability', response => {
        this.doctorAvailability = response.meta.availability;
      })

    }
  }
</script>
