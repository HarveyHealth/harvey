<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice container-flex">
                    <h1 class="heading-1">
                        <span class="text">Lab Test Inventory</span>
                        <button @click="newSkuModalOpen" class="button main-action circle">
                            <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                        </button>
                    </h1>
                </div>
            </div>
            <Flyout
                :active="activeModal"
                :heading="flyoutHeading"
                :on-close="modalClose"
            >
                <SkuForm :sku=selectedSku @append="appendSkuList" @saved="modalClose"/>
            </Flyout>
            <table class="sku-table tabledata appointments-table" v-if="loading">
                <td class="font-italic font-sm copy-muted">Loading lab tests inventory...</td>
            </table>
            <table class="sku-table tabledata appointments-table" v-if="!loading">
                <thead>
                    <th class="sku-table__column sku-table__move-icon heading-2 sort">Sort</th>
                    <th class="sku-table__column heading-2">Partner</th>
                    <th class="sku-table__column sku-table__sku-name heading-2">Lab Test</th>
                    <th class="sku-table__column heading-2">Sample</th>
                    <th class="sku-table__column heading-2">Description</th>
                    <th class="sku-table__column heading-2">Quote</th>
                    <th class="sku-table__column heading-2">Price</th>
                    <th class="sku-table__column heading-2">Cost</th>
                    <th class="sku-table__column heading-2">Public</th>
                </thead>
                <tbody class="copy-main">
                    <draggable v-model="skuList" @end="onDragComplete">
                        <Sku
                            v-for="sku in skuList"
                            :sku=sku
                            :key=sku.id
                            :selectedSku="selectedSku"
                            @click.native="modalOpenWithSku(sku)"
                        ></Sku>
                    </draggable>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue';
import Modal from '../../commons/Modal.vue';
import Flyout from '../../commons/Flyout.vue';
import Sku from './components/Sku.vue';
import SkuForm from './components/SkuForm.vue';
import draggable from 'vuedraggable';
import _ from 'lodash';

export default {
    name: 'SkuDashboard',
    components: {
        UserNav,
        Modal,
        Flyout,
        Sku,
        SkuForm,
        draggable,
    },
    data() {
        const blankSku = {
                attributes: {
                    name: null,
                    price: 0,
                    cost: 0,
                    lab_test_information: {
                        lab_name: null,
                        sample: null,
                        description: null,
                        quote: null,
                        image: null,
                        published_at: null,
                    }
                }
            };
        return {
            loading: true,
            activeModal: false,
            skuList: [blankSku],
            selectedSku: null,
        }
    },
    methods: {
      modalClose() {
        this.activeModal = false;
        setTimeout(() => this.clearSelectedSku(), 300);
      },
        newSkuModalOpen() {
          this.clearSelectedSku();
          this.modalOpen();
        },
        modalOpen() {
          this.activeModal = false;
          setTimeout(() => this.activeModal = true, 300);
        },
        setSelectedSku(sku) {
          this.selectedSku = sku;
        },
        clearSelectedSku() {
          this.selectedSku = null;
        },
        modalOpenWithSku(sku) {
          this.setSelectedSku(sku);
          this.modalOpen();
        },
        appendSkuList(sku) {
          this.skuList.push(sku);
        },
        onDragComplete() {
            this.skuList.map((sku, index) => {
                axios.patch(`${this.$root.$data.apiUrl}/skus/${sku.id}`, {
                    list_order: index,
                })
            });
        }
    },
    computed: {
        flyoutHeading() {
            return _.isEmpty(this.selectedSku) ? 'New Lab Test' : 'Update Lab Test';
        }
    },
    watch: {
    },
    created() {
        this.$root.$data.global.currentPage = 'sku-dashboard';

        axios.get(`${this.$root.$data.apiUrl}/skus?filter=lab-test`)
            .then(response => {
                this.skuList = _.orderBy(response.data.data, ['attributes.lab_test_information.list_order', 'asc']);
                this.loading = false;
            })
            .catch(e => console.log(e));
    }
}
</script>
