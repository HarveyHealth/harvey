<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                        <span class="text">Transactions</span>
                    </h1>
                </div>
            </div>
            <TransactionTable 
                :handle-row-click="handleRowClick"
                :loading="$root.$data.global.loadingTransactions"
                :selected-row="selectedRowData"
                :updating-row="selectedRowUpdating"
                :updated-row="selectedRowHasUpdated"
                :tableRowData="currentData"
                :filterSelected="activeFilter"
            />
        </div>
    </div>
</template>

<script>
    import TransactionTable from './components/TransactionTable.vue';
    import { formatTableData } from './utils/formatData';
    import { isEmpty } from 'lodash';
    export default {
        name: 'transactions',
        components: {
            TransactionTable
        },
        data() {
            return {
                selectedRowData: null,
                selectedRowUpdating: null,
                selectedRowHasUpdated: null,
                currentData: [],
                activeFilter: 0
            };
        },
        methods: {
            handleRowClick() {

            },
            setData(data) {
                this.currentData = data;
            }
        },
        computed: {
            currentDataState() {
                if (isEmpty(this.$root.$data.global.transactions)) {
                    return false;
                } else {
                    this.setData(formatTableData(this.$root.$data.global.transactions));
                    return true;
                }
            }
        },
        watch: {
            currentDataState(val) {
                if (val === false) {
                    this.setData(formatTableData(this.$root.$data.global.transactions));
                    return true;
                }
                return false;
            }
        },
        mounted() {
            this.$root.$data.global.currentPage = 'transactions';
        }
    };
</script>