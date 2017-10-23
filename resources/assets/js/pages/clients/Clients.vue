<template>
  <div class="main-container">
    <UserNav />
    <div class="main-content">
      <div class="main-header">
        <div class="container container-backoffice">
          <h1 class="heading-1">
            <span class="text">Recent Clients</span>
          </h1>
        </div>
      </div>

      <ClientsTable
          :handle-row-click="handleRowClick"
          :loading="loadingClients"
          :selected-row="{}"
          :updating-row="null"
          :updated-row="null"
          :tableRowData="currentData"
        />

    </div>
  </div>
</template>

<script>
import ClientsTable from './components/ClientsTable.vue';
import UserNav from '../../commons/UserNav.vue';
import tableDataTransform from './utils/tableData';
export default {
    name: 'Clients',
    components: {
        ClientsTable,
        UserNav
    },
    data() {
        return {
          currentData: []
        }
      },
      methods: {
        handleRowClick(obj, index) {
            return null;
        },
        $$rowClasses(data, index) {
            return {
                'is-selected': this.selectedRow === data,
                'is-updating': this.updatingRow === index,
                'has-updated': this.updatedRow === index
            };
        },
        setupLabData() {
            let data = tableDataTransform(this.$root.$data.clientList);
            this.currentData = data;
        },
        getLabTests() {
            this.tests = this.$root.$data.labTests;
        }
    },
    computed: {
        loadingClients() {
            return this.$root.$data.global.loadingClients;
        }
    },
    watch: {
        loadingClients(val) {
            if (!val) {
                this.setupLabData();
            }
        }
    },
    mounted() {
        this.$root.$data.global.currentPage = 'clients';
        const clientList = this.$root.$data.clientList;

        // only load if we have no local clients, but available clients in-app
        if (this.currentData.length === 0 && clientList.length != 0) {
            this.setupLabData();
        }
    }
};
</script>
