<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice container-flex">
                    <div class="heading-1">Edit Lab Tests</div>
                    <button @click="modalOpen" class="button is-primary">New Lab Test</button>
                </div>
            </div>
        </div>
        <Flyout
                :active="activeModal"
                heading="flyoutHeciading"
                :on-close="modalClose"
        />
    </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue'
import Modal from '../../commons/Modal.vue'
import Flyout from '../../commons/Flyout.vue'

export default {
    name: 'SkuDashboard',
    components: {
        UserNav,
        Modal,
        Flyout
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
        }
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
