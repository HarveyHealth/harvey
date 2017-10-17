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
                    :heading="flyoutHeading"
                    :on-close="modalClose"
            >
                <SkuForm :sku=selectedSku @append="appendSkuList"/>
            </Flyout>

            <div v-if="loading" class="copy-muted font-sm font-italic">Loading Lab Tests...</div>

            <div class="sku-table" v-if="!loading">
                <div class="sku-table__sku">
                    <div class="sku-table__column sku-table__move-icon"></div>
                    <div class="sku-table__column sku-table__sku-name">Lab Test</div>
                    <div class="sku-table__column">Lab Name</div>
                    <div class="sku-table__column">Sample</div>
                    <div class="sku-table__column">Description</div>
                    <div class="sku-table__column">Quote</div>
                    <div class="sku-table__column">Price</div>
                    <div class="sku-table__column">Cost</div>
                    <div class="sku-table__column">Public</div>
                </div>

                <draggable v-model="skuList" @end="onDragComplete">
                    <Sku
                        v-for="sku in skuList"
                        :sku=sku
                        :key=sku.id
                        @click.native="modalOpenWithSku(sku)"
                    ></Sku>
                </draggable>
            </div>
        </div>
        <Flyout
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
            selectedSku: blankSku,
            skuList: [blankSku],
        }
    },
    methods: {
      modalClose() {
        this.activeModal = false;
        setTimeout(() => this.selectedSku = null, 300);
      },
        modalOpen() {
          this.activeModal = true;
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
            return this.selectedSku ? 'Update Lab Test' : 'New Lab Test';
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

<style lang="scss">
  .container-flex {
    display: flex;
    justify-content: space-between;
  }

    .sku-table {
        display: flex;
        justify-content: space-between;
        flex-direction: column;
    }
</style>
