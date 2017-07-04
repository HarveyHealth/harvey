<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="title header-xlarge">
                    <span class="text">Lab Orders</span>
                    <button v-if="!isEmpty($root.$data.labTests) && $root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'admin'" v-on:click="addingFlyoutActive()" class="button main-action circle">
                        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                    </button>
                    </h1>
                    <FilterButtons
                        :active-filter="activeFilter"
                        :filters="filters"
                        :loading="disabledFilters"
                        :on-filter="handleFilter"
                    />
                </div>
            </div>
            <NotificationPopup
              :active="notificationActive"
              :comes-from="notificationDirection"
              :symbol="notificationSymbol"
              :text="notificationMessage"
            />
            <AddLabOrders v-if="labTests && $root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'admin'"
            :reset="setupLabData" />
            <DetailLabOrders v-if="currentData" :row-data="selectedRowData" :reset="setupLabData" />
            <Overlay
                :active="addFlyoutActive"
                :onClick="addingFlyoutActive"
            />
            <LabOrderTable
                :handle-row-click="handleRowClick"
                :loading="$root.$data.global.loadingLabOrders && $root.$data.global.loadingLabTests"
                :selected-row="selectedRowData"
                :updating-row="selectedRowUpdating"
                :updated-row="selectedRowHasUpdated"
                :tableRowData="currentData"
             />
        </div>
    </div>
</template>

<script>
    import UserNav from '../../commons/UserNav.vue'
    import Overlay from '../../commons/Overlay.vue'
    import NotificationPopup from '../../commons/NotificationPopup.vue'
    import FilterButtons from '../../commons/FilterButtons.vue'
    import LabOrderTable from './components/LabOrderTable.vue'
    import AddLabOrders from './components/AddLabOrders.vue'
    import DetailLabOrders from './components/DetailLabOrders.vue'
    import tableDataTransform from './utils/tableDataTransform'
    import _ from 'lodash'

    export default {
        name: 'LabOrders',
        components: {
            UserNav,
            LabOrderTable,
            AddLabOrders,
            Overlay,
            DetailLabOrders,
            FilterButtons,
            NotificationPopup
        },
        data() {
            return {
                filters: ['All', 'Pending', 'Completed'],
                activeFilter: 0,
                selectedRowData: null,
                selectedRowUpdating: null,
                selectedRowHasUpdated: null,
                addFlyoutActive: false,
                detailFlyoutActive: false,
                cache: {
                    All: [],
                    Pending: [],
                    Completed: []
                },
                currentData: [],
                notificationSymbol: '&#10003;',
                notificationMessage: '',
                notificationActive: false,
                notificationDirection: 'top-right'
            }
        },
        methods: {
            handleRowClick(obj, index) {
                let data;
                if (obj) {
                    data = obj.data === this.selectedRowData ? null : obj.data;
                } else {
                    data = null;
                }

                this.detailFlyoutActive = !this.detailFlyoutActive

                if (data) {
                    this.selectedRowData = data;
                    this.selectedRowIndex = index;
                } else {
                    this.selectedRowData = null;
                    this.selectedRowIndex = null;
                    this.flyoutActive = false;
                }
            },
            isEmpty(obj) {
                return _.isEmpty(obj)
            },
            handleFilter(name, index) {
                this.activeFilter = index;
                switch (name) {
                    case "All":
                        this.currentData = this.cache.All
                        break;
                    case "Pending":
                        this.currentData = this.cache.Pending
                        break;
                    case "Completed":
                        this.currentData = this.cache.Completed
                        break;
                }
            },
            $$rowClasses(data, index) {
                return {
                    'is-selected': this.selectedRow === data,
                    'is-updating': this.updatingRow === index,
                    'has-updated': this.updatedRow === index,
                }
            },
            addingFlyoutActive() {
                this.detailFlyoutActive = false
                this.addFlyoutActive = !this.addFlyoutActive
            },
            detailsFlyoutActive() {
                if (this.selectedRowData != null) this.selectedRowData = null;
                this.addFlyoutActive = !this.addFlyoutActive
                this.detailFlyoutActive = !this.detailFlyoutActive
            },
            setupLabData() {
                let global = this.$root.$data.global
                let patient = null
                if (global.user.attributes && global.user.attributes.user_type == 'patient') {
                    patient = {}
                    patient[global.user.included.id] = global.user.included
                    patient[global.user.included.id].attributes.id = global.user.included.id
                } else {
                    patient = global.patientLookUp
                }
                let data = tableDataTransform(
                    global.labOrders, 
                    global.labTests, 
                    patient, 
                    global.practitionerLookUp,
                    this.$root.$data.labTests
                )
                let choices = {
                    0: "All",
                    1: "Pending",
                    2: "Completed"
                }
                this.cache[choices['0']] = data
                this.cache[choices['1']] = data.filter(e => e.data.completed_at != "Complete" && e.data.completed_at != "Canceled")
                this.cache[choices['2']] = data.filter(e => e.data.completed_at == "Complete")
                this.currentData = data
            }
        },
        computed: {
            disabledFilters() {
                return this.$root.$data.global.loadingLabOrders || this.$root.$data.global.loadingLabTests || this.selectedRowUpdating !== null;
            },
            loadingLabOrders() {
                return this.$root.$data.global.loadingLabOrders
            },
            loadingLabTests() {
                return this.$root.$data.global.loadingLabTests
            },
            loadingLabs() {
                const global = this.$root.$data.global
                return global.loadingLabTests && global.loadingLabOrders && global.labOrders && global.labTests && this.$root.$data.labTests
            },
            labTests() {
                return this.$root.$data.labTests
            }
        },
        watch: {
            loadingLabs(val, old) {
                if (!val) {
                    setTimeout(() => this.setupLabData(), 1800)
                }
            }
        },
        mounted() {
            this.$root.$data.global.currentPage = 'lab-orders';

            const labOrders = this.$root.$data.global.labOrders
            const labTests = this.$root.$data.global.labTests
            if (labOrders.length && labTests.length) this.setupLabData();
        }
    }
</script>