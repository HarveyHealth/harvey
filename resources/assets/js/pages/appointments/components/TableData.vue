<template>
  <div>
    <table class="tabledata" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th width="15%">Date</th>
          <th width="10%">Time</th>
          <th width="15%">Client</th>
          <th width="20%">Doctor</th>
          <th width="10%">Status</th>
          <th width="30%">Purpose</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="!tabledata.length">
          <td colspan="6" class="card-empty-container">
              <p>You have no upcoming appointments.</p>
          </td>
        </tr>
        <tr v-for="row in tabledata" @click="rowClick(row, $event)">
          <td v-if="row.attributes.appointment_at.date">{{ toLocalTimezone(row.attributes.appointment_at.date, $root.timezone) | tableDate }}</td>
            <td v-else=""></td>
          <td v-if="row.attributes.appointment_at.date">{{ toLocalTimezone(row.attributes.appointment_at.date, $root.timezone) | tableTime }}</td>
            <td v-else=""></td>
          <td v-if="row.patientData.last_name">{{ row.patientData.first_name | capitalize }} {{ row.patientData.last_name | capitalize }}</td>
            <td v-else=""></td>
          <td v-if="row.attributes.practitioner_name">Dr. {{ row.attributes.practitioner_name }}</td>
            <td v-else=""></td>
          <td v-if="row.attributes.status">{{ row.attributes.status | tableStatus }}</td>
            <td v-else=""></td>
          <td v-if="row.attributes.reason_for_visit">{{ row.attributes.reason_for_visit }}</td>
            <td v-else=""></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

import { capitalize } from '../../../utils/filters/textformat';
import combineAppointmentData from '../utils/combineAppointmentData';
import convertStatus from '../utils/statuses';
import moment from 'moment-timezone';
import toLocalTimezone from '../../../utils/methods/toLocalTimezone';

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
      return d.format('dddd, MMM Do');
    },
    tableStatus(s) {
      return convertStatus(s);
    },
    tableTime(t) {
      return t.format('h:mm a');
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
    },
    toLocalTimezone
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
