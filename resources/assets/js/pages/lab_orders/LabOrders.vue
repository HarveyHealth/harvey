<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container">
                    <h1 class="title header-xlarge">
                    <span class="text">Lab Orders</span>
                    <button v-on:click="addingFlyoutActive()" class="button main-action circle">
                        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                    </button>
                    </h1>
                </div>
            </div>
            <AddLabOrders />
            <Overlay 
                :active="addFlyoutActive"
                :onClick="addingFlyoutActive"
            />
            <!--
            <LabOrderTable
                :handle-row-click="handleRowClick"
                :loading="$root.$data.global.loadingLabOrders && $root.$data.global.loadingLabTests"
                :selected-row="selectedRowData"
                :updating-row="selectedRowUpdating"
                :updated-row="selectedRowHasUpdated"
                :tableRowData="labData"
             /> 
            -->
        </div>
    </div>
</template>

<script>
    import UserNav from '../../commons/UserNav.vue'
    import Overlay from '../../commons/Overlay.vue'
    import LabOrderTable from './components/LabOrderTable.vue'
    import AddLabOrders from './components/AddLabOrders.vue'
    export default {
        name: 'LabOrders',
        components: {
            UserNav,
            LabOrderTable,
            AddLabOrders,
            Overlay
        },
        data() {
            return {
                selectedRowData: null,
                selectedRowUpdating: null,
                selectedRowHasUpdated: null,
                labData: [],
                addFlyoutActive: false
            }
        },
        methods: {
            handleRowClick(obj, index) {

            },
            addingFlyoutActive() {
                this.addFlyoutActive = !this.addFlyoutActive
            }
        },
        mounted() {
            this.$root.$data.global.currentPage = 'lab-orders';
        }
    }
</script>