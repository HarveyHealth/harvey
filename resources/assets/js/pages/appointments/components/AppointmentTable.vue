<template>
  <TableData
    :columns="tableColumns"
    :empty-msg="'No appointments found.'"
    :loading="loading"
    :loading-msg="'Loading your appointments...'"
    :on-row-click="handleRowClick"
    :row-data="tableData"
    :selected-row="selectedRow"
    :table-class="'appointments-table'"
    :updating-row="updatingRow"
  />
</template>

<script>
import TableData from '../../../commons/TableData.vue';
import tableColumns from '../utils/tableColumns';
import tableSort from '../../../utils/methods/tableSort';

export default {
  data() {
    return {
      tableColumns,
    }
  },
  components: {
    TableData,
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
    },
  },
  props: {
    handleRowClick: {
      type: Function,
    },
    loading: {
      type: Boolean,
      required: true
    },
    refresh: {
      type: Function,
      required: true
    },
    reset: {
      type: Function,
      required: true
    },
    selectedRow: {
      type: Object,
    },
    tableRowData: {
      type: Array,
      required: true
    },
    updatingRow: {
      required: false
    },
  }
}
</script>
