<template>
    <TableData 
        :columns="tableColumns"
        :empty-msg="'You\'ve been no transactions.'"
        :loading="loading"
        :loading-msg="'Loading your transactions...'"
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
    import { tableCoulmns } from '../utils/tableColumns';
    export default {
        name: 'TransactionsTable',
        components: {
            TableData
        },
        data() {
            return {
                tableCoulmns
            };
        },
        computed: {
            tableData() {
                return this.tableRowData;
            }
        },
        watch: {

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