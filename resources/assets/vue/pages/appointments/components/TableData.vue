<template>
  <div>
    <table class="tabledata" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th width="15%">Date</th>
          <th width="10%">Time</th>
          <th width="15%">Client</th>
          <th width="15%">Doctor</th>
          <th width="10%">Status</th>
          <th width="35%">Purpose</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="!tabledata.length">
          <td colspan="6">Loading your appointment details</td>
        </tr>
        <tr v-for="row in tabledata" @click="rowClick(row, $event)">
          <td>{{ row.attributes.appointment_at.date | tableDate }}</td>
          <td>{{ row.attributes.appointment_at.date | tableTime }}</td>
          <td>{{ row.patientData.last_name | capitalize }}, {{ row.patientData.first_name | capitalize }}</td>
          <td>Dr. {{ row.attributes.practitioner_name }}</td>
          <td>{{ row.attributes.status | tableStatus }}</td>
          <td>{{ row.attributes.reason_for_visit }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

import { capitalize } from '../../../utils/filters/textformat';
import combineAppointmentData from '../utils/combineAppointmentData';
import convertStatus from '../utils/statuses';
import moment from 'moment';

export default {
  props: ['allTableData', 'config'],
  data() {
    return {
      tabledata: [],
    }
  },
  filters: {
    capitalize(word) {
      return capitalize(word);
    },
    tableDate(d) {
      return moment(d).format('ddd MMM Do');
    },
    tableStatus(s) {
      return convertStatus(s);
    },
    tableTime(t) {
      return moment(t).format('h:mm a');
    }
  },
  methods: {
    getAppointmentData() {
      axios.get('/api/v1/appointments?include=patient.user').then(response => {
        const newData = combineAppointmentData(response.data).reverse();
        this.tabledata = newData;
      })
    },
    rowClick(rowData, event) {
      let classname = event.target.parentElement.className;
      this.$eventHub.$emit('deselectRows');
      event.target.parentElement.className = classname === '' ? 'isactive' : '';
      this.$eventHub.$emit('rowClickEvent', rowData, classname !== '');
    }
  },
  mounted() {

    this.$eventHub.$on('deselectRows', () => {
      document.querySelectorAll('tr.isactive').forEach(n => n.className = '');
    })

    this.$eventHub.$on('refreshTable', this.getAppointmentData);

    this.getAppointmentData();

  },
  destroyed() {
    this.$eventHub.$off('deselectRows');
    this.$eventHub.$off('refreshTable');
  }
}
</script>
