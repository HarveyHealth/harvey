<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice container-flex">
                    <h1 class="heading-1">
                        <span class="text">Edit Lab Tests</span>
                    </h1>
                    <button @click="modalOpen" class="button is-primary">New Lab Test</button>
                </div>
            </div>
            <Flyout
                    :active="activeModal"
                    heading="flyoutHeading"
                    :on-close="modalClose"
            />
            <div class="sku-table" v-for="sku in currentData">
                <Sku :sku="sku" />
            </div>
        </div>
    </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue';
import Modal from '../../commons/Modal.vue';
import Flyout from '../../commons/Flyout.vue';
import Sku from './components/Sku.vue';

export default {
    name: 'SkuDashboard',
    components: {
        UserNav,
        Modal,
        Flyout,
        Sku,
    },
    data() {
        return {
            activeModal: false,
            selectedRowData: null,
            selectedRowUpdating: null,
            selectedRowHasUpdated: null,
            currentData: [],
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
        getLabTestSkus() {
        }
    },
    computed: {
    },
    watch: {
    },
    mounted() {
        this.$root.$data.global.currentPage = 'sku-dashboard';

        axios.get(`${this.$root.$data.apiUrl}/skus?filter=lab-test`)
            .then(response => {
                this.currentData = response.data.data;
            })
            .catch(e => console.log(e));
    }
}
</script>

<style lang="scss">
  .container-flex {
    display: flex;
    justify-content: space-between;
  }

    .sku-table {
        display: flex;
        justify-content: space-between;
    }
</style>
