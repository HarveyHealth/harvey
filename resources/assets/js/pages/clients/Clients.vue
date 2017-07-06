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
          :loading="loadingClients"
          :selected-row="null"
          :updating-row="null"
          :updated-row="null"
          :tableRowData="currentData"
        />

    </div>
  </div>
</template>

<script>
import ClientsTable from './components/ClientsTable.vue'
import UserNav from '../../commons/UserNav.vue'
import tableDataTransform from './utils/tableData'

export default {
    name: 'Clients',
    components: {
      ClientsTable,
      UserNav
    },
    data() {
      return {
        currentData: null
      }
    },
    methods: {
      handleRowClick(obj, index) {
          return null
      },
      $$rowClasses(data, index) {
          return {
              'is-selected': this.selectedRow === data,
              'is-updating': this.updatingRow === index,
              'has-updated': this.updatedRow === index,
          }
      },
      setupLabData() {
          let data = tableDataTransform(this.$root.$data.clientList)
          this.currentData = data
      },
      getLabTests() {
          this.tests = this.$root.$data.labTests
      }
  },
  computed: {
      loadingClients() {
          return this.$root.$data.global.loadingClients
      }
  },
  watch: {
      loadingClients(val, old) {
          if (!val) {
              this.setupLabData()
          }
      }
  },
  mounted() {
      this.$root.$data.global.currentPage = 'clients';

      const clientList = this.$root.$data.global.clientList
      if (clientList.length) this.setupLabData();
  } 
}
</script>
