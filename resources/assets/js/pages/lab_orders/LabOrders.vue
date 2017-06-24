<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="title header-xlarge">
                    <span class="text">Lab Orders</span>
                    <button v-if="$root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'admin'" v-on:click="addingFlyoutActive()" class="button main-action circle">
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
            <AddLabOrders v-if="$root.$data.global.user.attributes && $root.$data.global.user.attributes.user_type === 'admin'" />
            <DetailLabOrders :row-data="selectedRowData" />
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
                :tableRowData="labData"
             />
        </div>
    </div>
</template>

<script>
    import UserNav from '../../commons/UserNav.vue'
    import Overlay from '../../commons/Overlay.vue'
    import FilterButtons from '../../commons/FilterButtons.vue'
    import LabOrderTable from './components/LabOrderTable.vue'
    import AddLabOrders from './components/AddLabOrders.vue'
    import DetailLabOrders from './components/DetailLabOrders.vue'
    import tableDataTransform from './utils/tableDataTransform'

    export default {
        name: 'LabOrders',
        components: {
            UserNav,
            LabOrderTable,
            AddLabOrders,
            Overlay,
            DetailLabOrders,
            FilterButtons
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
                cache: {}
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
                }
            },
            handleFilter(name, index) {
                this.activeFilter = index;
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
                this.addFlyoutActive = !this.addFlyoutActive
                this.detailFlyoutActive = !this.detailFlyoutActive
            },
            setupLabData() {
                this.cache.All = tableDataTransform(
                    this.$root.$data.global.labOrders, 
                    this.$root.$data.global.labTests, 
                    this.$root.$data.global.patientLookUp, 
                    this.$root.$data.global.practitionerLookUp
                )
                return this.cache.All
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
            labData() {
                let data = tableDataTransform(
                    this.$root.$data.global.labOrders, 
                    this.$root.$data.global.labTests, 
                    this.$root.$data.global.patientLookUp, 
                    this.$root.$data.global.practitionerLookUp
                )
                return data
            }
        },
        watch: {
            labData(val) {
                if (!val) {
                    this.setupLabData()
                }
            }
        },
        mounted() {
            this.$root.$data.global.currentPage = 'lab-orders';
        }
    }
</script>