<template>
  <TableData
      :columns="tableColumns"
      empty-msg="No schedule overrides have been set."
      :loading="loading"
      :loading-msg="'Loading your schedule overrides...'"
      :on-row-click="handleRowClick"
      :row-data="tableData"
      :selected-row="selectedRow"
      :table-class="'schedule-table'"
      :updated-row="updatedRow"
      :updating-row="updatingRow"
  />
</template>

<script>
import TableData from '../../../commons/TableData.vue';
import tableColumns from '../utils/overrideTableColumns';

export default {
  name: 'OverrideTable',
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
    handleRowClick: {
      type: Function
    },
    filterSelected: {
      type: Number
    },
    loading: {
      type: Boolean,
      required: true
    },
    selectedRow: {
      type: Object,
      require: false
    },
    tableRowData: {
      type: Array,
      required: true
    },
    updatedRow: {
      required: false,
      type: Number
    },
    updatingRow: {
      required: false,
      type: Number
    }
  }
};
</script>
