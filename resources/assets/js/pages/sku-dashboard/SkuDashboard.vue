<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice container-flex">
                    <h1 class="heading-1">
                        <span class="text">Edit Lab Tests</span>
                    </h1>
                    <button @click="modalOpen" class="button is-primary">New Lab Test</button>
                </div>
            </div>
        </div>
        <Flyout
                :active="activeModal"
                heading="flyoutHeading"
                :on-close="modalClose"
        />
        <SkusTable
            :handle-row-click="handleRowClick"
            :loading="loadingLabs"
            :selected-row="selectedRowData"
            :updating-row="selectedRowUpdating"
            :updated-row="selectedRowHasUpdated"
            :tableRowData="currentData"
         />
    </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue'
import Modal from '../../commons/Modal.vue'
import Flyout from '../../commons/Flyout.vue'
import SkusTable from './components/SkusTable.vue'

export default {
    name: 'SkuDashboard',
    components: {
        UserNav,
        Modal,
        Flyout,
        SkusTable
    },
    data() {
        return {
            activeModal: false
        }
    },
    methods: {
      modalClose() {
        this.activeModal = false;
      },
        modalOpen() {
          this.activeModal = true;
        },
        handleRowClick() {
            console.log('click');
        },
    },
    computed: {
    },
    watch: {
    },
    mounted() {
        this.$root.$data.global.currentPage = 'sku-dashboard';
    }
}
</script>

<style lang="scss">
  .container-flex {
    display: flex;
    justify-content: space-between;
  }
</style>
