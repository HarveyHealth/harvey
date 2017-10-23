<template>
  <TableData
    :columns="tableColumns"
    :empty-msg="'You do not have any appointments.'"
    :loading="loading"
    :loading-msg="'Loading your appointments...'"
    :on-row-click="handleRowClick"
    :row-data="tableData"
    :selected-row="selectedRow"
    :table-class="'appointments-table'"
    :updated-row="updatedRow"
    :updating-row="updatingRow"
  />
</template>

<script>
// AppointmentTable is the wrapper component around TableData used to
// manage state for the Appointments component

import TableData from '../../../commons/TableData.vue';
import tableColumns from '../utils/tableColumns';
import tableSort from '../../../utils/methods/tableSort';

export default {
  data() {
    return {
      tableColumns
    };
  },
  components: {
    TableData
  },
  computed: {
    // Needs to be computed to respond to global state change
    // Also, default sort by date for now until more robust sort is created
    tableData() {
      return this.tableRowData;
    }
  },
  methods: {
    handleSort(colObj) {
      // computed properties can be mutated, apparently?
      this.tableData.sort(colObj.sort);
    }
  },
  props: {
    // Passed from Appointments so we can modify the appointment data and trigger
    // other things within Appointments
    handleRowClick: {
      type: Function
    },
    // Passed from Appointments because it is waiting for the app.js Promise to resolve
    loading: {
      type: Boolean,
      required: true
    },
    // See TableData
    selectedRow: {
      type: Object
    },
    // See TableData
    tableRowData: {
      type: Array,
      required: true
    },
    // See TableData
    updatedRow: {
      required: false
    },
    // See TableData
    updatingRow: {
      required: false
    }
  }
};
</script>
