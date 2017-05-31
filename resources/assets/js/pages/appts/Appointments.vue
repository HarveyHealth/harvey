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

      <TableData :columns="tableColumns" :tabledata="_appointments" />

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
    </Flyout>

    <Overlay :active="overlayActive" />

  </div>
</template>

<script>
// components
import Flyout from '../../commons/Flyout2.vue';
import Overlay from '../../commons/Overlay2.vue';
import Patient from './components/Patient.vue';
import TableData from '../../commons/TableData.vue';
import UserNav from '../../commons/UserNav.vue';

// other
import tableColumns from './tableColumns';
import tableDataTransform from './tableDataTransform';

export default {
  data() {
    return {
      appointment: {
        date: '',
        doctorId: '',
        id: '',
        status: '',
        patientEmail: '',
        patientId: '',
        patientName: '',
        patientPhone: '',
        purpose: ''
      },
      flyoutActive: false,
      flyoutHeading: '',
      flyoutMode: null,
      overlayActive: false,
      patientList: [],
      selectedRowData: null,
      selectedRowIndex: null,
      tableColumns,
      userType: Laravel.user.userType
    }
  },
  name: 'appts',
  components: {
    Flyout,
    Overlay,
    Patient,
    TableData,
    UserNav
  },
  computed: {
    _appointments() {
      return tableDataTransform(this.$root.$data.global.appointments);
    },
    editablePatient() {
      return this.userType !== 'patient' && this.flyoutMode === 'new'
    },
    visiblePatient() {
      return this.userType !== 'patient';
    }
  },

  methods: {

    handleNewAppointmentClick() {
      if (this.userType !== 'patient' && this.patientList.length) {
        this.appointment.patientEmail = this.patientList[0].data.email;
        this.appointment.patientName = this.patientList[0].data.name;
        this.appointment.patientId = this.patientList[0].data.id;
        this.appointment.patientPhone = this.patientList[0].data.phone;
      }
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
    }

  },

  mounted() {

    // When the flyout closes, close the overlay and unselect any table rows
    this.$eventHub.$on('closeFlyout', () => {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
      this.selectedRowData = null;
      this.selectedRowIndex = null;
      this.resetAppointment();
      this.$eventHub.$emit('tableRowUnselect');
    });

    // Assign patients to patientList when the Promise resolves
    this.$eventHub.$on('receivedPatients', list => {
      this.patientList = list.map(item => {
        return { value: item.name, data: item };
      });
      if (this.userType !== 'patient' && this.flyoutMode === 'new') {
        this.appointment.patientEmail = this.patientList[0].data.email;
        this.appointment.patientName = this.patientList[0].data.name;
        this.appointment.patientId = this.patientList[0].data.id;
        this.appointment.patientPhone = this.patientList[0].data.phone;
      }
    });

    this.$eventHub.$on('selectPatient', patient => {
      this.appointment.patientId = patient.data.id;
      this.appointment.patientEmail = patient.data.email;
      this.appointment.patientName = patient.data.name;
      this.appointment.patientPhone = patient.data.phone;
    })

    // On row click, set heading and open flyout, or close flyout
    this.$eventHub.$on('tableRowClick', (obj, index) => {
      this.selectedRowData = obj;
      this.selectedRowIndex = index;
      if (obj) {
        this.appointment.patientEmail = obj.rowData._patientEmail;
        this.appointment.patientName = `${obj.rowData._patientLast}, ${obj.rowData._patientFirst}`;
        this.appointment.patientPhone = obj.rowData._patientPhone;
        this.flyoutHeading = 'Update Appointment';
        this.flyoutMode = 'update';
        this.flyoutActive = true;
      } else {
        this.flyoutActive = false;
        this.flyoutMode = null;
        this.selectedRowData = null;
        this.selectedRowIndex = null;
        this.resetAppointment();
      }
    });

    // When the overlay is closed because it was clicked, close the flyout
    this.$eventHub.$on('overlayClicked', () => {
      this.flyoutActive = false;
      this.flyoutMode = null;
      this.overlayActive = false;
    });

  },
  destroyed() {
    this.$eventHub.$off('closeFlyout');
    this.$eventHub.$off('receivedPatients');
    this.$eventHub.$off('overlayClicked');
    this.$eventHub.$off('tableRowClick');
  }
}
</script>
