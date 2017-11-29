<template>
  <TableData 
      :columns="tableColumns"
      :empty-msg="emptyString"
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
    },
    emptyString() {
        return `No lab orders in ${
                this.$props.filterSelected === 0 ?
                    "recommended" :
                this.$props.filterSelected === 1 ?
                    "confirmed" :
                this.$props.filterSelected === 2 ?
                    "shipped" :
                this.$props.filterSelected === 3 ?
                    "received" :
                this.$props.filterSelected === 4 ?
                    "mailed" :
                this.$props.filterSelected === 5 ?
                    "processing" :
                this.$props.filterSelected === 6 ?
                    "complete" : ''
      } category.`;
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
    filterSelected: {
      type: Number
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
