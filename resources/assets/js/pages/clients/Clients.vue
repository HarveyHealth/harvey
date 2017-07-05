<template>
  <div class="main-container">
    <UserNav />
    <div class="main-content">
      <div class="main-header">
        <div class="container container-backoffice">
          <h1 class="title header-xlarge">
            <span class="text">Recent Clients</span>
          </h1>  
        </div>
      </div>

      <ClientsTable
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
import ClientsTable from './components/ClientsTable.vue'
import UserNav from '../../commons/UserNav.vue'

export default {
    name: 'Clients',
    components: {
      ClientsTable,
      UserNav
    },
    data() {
      return {

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
              this.detailFlyoutActive = false;
          }
      },
      $$rowClasses(data, index) {
          return {
              'is-selected': this.selectedRow === data,
              'is-updating': this.updatingRow === index,
              'has-updated': this.updatedRow === index,
          }
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
          this.currentData = data
      },
      getLabTests() {
          this.tests = this.$root.$data.labTests
      }
  },
  computed: {
      loadingLabOrders() {
          return this.$root.$data.global.loadingLabOrders
      },
      loadingLabTests() {
          return this.$root.$data.global.loadingLabTests
      },
      loadingLabs() {
          const global = this.$root.$data.global
          return global.loadingLabTests && global.loadingLabOrders && global.labOrders && global.labTests && this.$root.$data.labTests
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
      this.$root.$data.global.currentPage = 'clients';

      const labOrders = this.$root.$data.global.labOrders
      const labTests = this.$root.$data.global.labTests
      if (labOrders.length && labTests.length) this.setupLabData();
  } 
}
</script>
