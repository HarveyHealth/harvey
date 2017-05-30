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
        <tr v-if="!tableData.length">
          <td colspan="6" class="card-empty-container">
              <p>You have no upcoming appointments.</p>
          </td>
        </tr>
        <tr v-for="row in tableData" @click="rowClick(row, $event)">
          <td v-if="row.attributes.appointment_at.date">{{ row.attributes.appointment_at.date | tableDate }}</td>
            <td v-else=""></td>
          <td v-if="row.attributes.appointment_at.date">{{ row.attributes.appointment_at.date | tableTime }}</td>
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
import convertStatus from '../utils/statuses';
import moment from 'moment-timezone';
import toLocalTimezone from '../../../utils/methods/toLocalTimezone';

export default {
  props: ['allTableData', 'config'],
  computed: {
    tableData() {
      return this.$root.$data.global.appointments;
    },
  },
  filters: {
    capitalize(word) {
      return capitalize(word);
    },
    tableDate(d) {
      return moment.utc(d).local().format('dddd, MMM Do');
    },
    tableStatus(s) {
      return convertStatus(s);
    },
    tableTime(t) {
      return moment.utc(t).local().format('h:mm a');
    }
  },
  methods: {
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

    this.$eventHub.$on('refreshTable', this.$root.getAppointments);

  },
  destroyed() {
    this.$eventHub.$off('deselectRows');
    this.$eventHub.$off('refreshTable');
  }
}
</script>
