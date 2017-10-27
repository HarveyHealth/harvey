<template>
  <TableData 
      :columns="tableColumns"
      :empty-msg="'No lab orders in this category.'"
      :loading="loading"
      :loading-msg="'Loading your lab orders...'"
      :on-row-click="handleRowClick"
      :row-data="tableData"
      :selected-row="selectedRow"
      :table-class="'appointments-table'"
      :updated-row="updatedRow"
      :updating-row="updatingRow"
  />
</template>

<script>
import TableData from '../../../commons/TableData.vue';
import tableColumns from '../utils/tableColumns';

export default {
  name: 'LabOrderTable',
  components: {
    TableData
  },
  data() {
      return {
        tableColumns
      };
  },
  computed: {
    tableData() {
      return this.tableRowData;
    }
  },
  methods: {
    handleSort(colObj) {
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
      type: Object,
      require: false
    },
    // See TableData
    tableRowData: {
      type: Array,
      required: true
    },
    // See TableData
    updatedRow: {
      required: false,
      type: Number
    },
    // See TableData
    updatingRow: {
      required: false,
      type: Number
    }
  }
};
</script>
