<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="title header-xlarge">
                    <span class="text">Lab Orders</span>
                    <button v-if="!loadingLabs && $root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'admin'" v-on:click="addingFlyoutActive()" class="button main-action circle">
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
            <AddLabOrders v-if="!loadingLabs && $root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type !== 'patient'"
            :reset="setupLabData" :labTests="tests" />
            <DetailLabOrders v-if="currentData" :row-data="selectedRowData" :reset="setupLabData" />
            <Overlay
                :active="addFlyoutActive"
                :onClick="addingFlyoutActive"
            />
            <LabOrderTable
                :handle-row-click="handleRowClick"
                :loading="loadingLabs"
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
                filters: ['Recommended', 'Confirmed', 'Shipped', 'Received', 'Mailed', 'Processing', 'Complete'],
                activeFilter: 0,
                selectedRowData: null,
                selectedRowUpdating: null,
                selectedRowHasUpdated: null,
                addFlyoutActive: false,
                detailFlyoutActive: false,
                addActiveModal: false,
                cache: {
                    Recommended: [],
                    Confirmed: [],
                    Shipped: [],
                    Received: [],
                    Mailed: [],
                    Processing: [],
                    Complete: []
                },
                tests: null,
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
                    this.detailFlyoutActive = true;
                    this.selectedRowData = data;
                    this.selectedRowIndex = index;
                } else {
                    this.selectedRowData = null;
                    this.selectedRowIndex = null;
                    this.detailFlyoutActive = false;
                }
            },
            handleFilter(name, index) {
                this.activeFilter = index;
                switch (name) {
                    case "Recommended":
                        this.currentData = this.cache.Recommended
                        break;
                    case "Confirmed":
                        this.currentData = this.cache.Confirmed
                        break;
                    case "Shipped":
                        this.currentData = this.cache.Shipped
                        break;
                    case "Received":
                        this.currentData = this.cache.Received
                        break;
                    case "Mailed":
                        this.currentData = this.cache.Mailed
                        break;
                    case "Processing":
                        this.currentData = this.cache.Processing
                        break;
                    case "Complete":
                        this.currentData = this.cache.Complete
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
                    0: "Recommended",
                    1: "Confirmed",
                    2: "Shipped",
                    3: "Received",
                    4: "Mailed",
                    5: "Processing",
                    6: "Complete"
                }
                data.sort((a,b) => new Date(b.data.date) - new Date(a.data.date))
                this.cache[choices['0']] = data.filter(e => e.data.completed_at == "Recommended")
                this.cache[choices['1']] = data.filter(e => e.data.completed_at == "Confirmed")
                this.cache[choices['2']] = data.filter(e => e.data.completed_at == "Shipped")
                this.cache[choices['3']] = data.filter(e => e.data.completed_at == "Received")
                this.cache[choices['4']] = data.filter(e => e.data.completed_at == "Mailed")
                this.cache[choices['5']] = data.filter(e => e.data.completed_at == "Processing")
                this.cache[choices['6']] = data.filter(e => e.data.completed_at == "Complete")
                this.currentData = data.filter(e => e.data.completed_at == "Recommended")
            },
            getLabTests() {
                this.tests = this.$root.$data.labTests
            }
        },
        computed: {
            disabledFilters() {
                return this.$root.$data.global.loadingLabOrders || this.$root.$data.global.loadingLabTests || this.selectedRowUpdating !== null;
            },
            loadingLabs() {
                const global = this.$root.$data.global
                let attributes = this.$root.$data.global.user.attributes
                if (attributes && attributes.user_type !== 'patient') {
                    return global.loadingLabTests || 
                    global.loadingLabOrders || 
                    global.loadingPatients || 
                    global.loadingPractitioners
                } else {
                    return global.loadingLabTests || 
                    global.loadingLabOrders || 
                    global.loadingPractitioners
                }
            },
            labTests() {
                this.tests = this.$root.$data.labTests
                return this.$root.$data.labTests.length > 0
            }
        },
        watch: {
            loadingLabs(val, old) {
                if (!val) {
                    this.setupLabData()
                }
            },
            labTests(val) {
                if (!val) {
                    this.getLabTests()
                }
            }
        },
        mounted() {
            this.$root.$data.global.currentPage = 'lab-orders';
            const global = this.$root.$data.global
            let attributes = this.$root.$data.global.user.attributes

            if (!global.loadingLabTests && 
                !global.loadingLabOrders && 
                !global.loadingPractitioners &&
                (!global.loadingPatients || (attributes && attributes.user_type === 'patient'))) {
                    this.setupLabData();
                }
        }
    }
</script>