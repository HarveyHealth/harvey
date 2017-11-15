<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                        <span class="text">Lab Orders</span>
                        <button
                          v-if="!loadingLabs && $root.$data.permissions !== 'patient'"
                          v-on:click="addingFlyoutActive()"
                          class="button main-action circle"
                          data-test="addLabOrder"
                        >
                            <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                        </button>
                    </h1>
                    <FilterButtons
                        :flyout="closeFlyouts"
                        v-if="$root.$data.permissions !== 'patient'"
                        :active-filter="activeFilter"
                        :filters="filters"
                        :loading="disabledFilters"
                        :on-filter="handleFilter"
                        :all-data="labData"
                    />
                </div>
            </div>
            <NotificationPopup
              :active="notificationActive"
              :comes-from="notificationDirection"
              :symbol="notificationSymbol"
              :text="notificationMessage"
            />
            <AddLabOrders v-if="!loadingLabs && $root.$data.permissions != 'patient'"
            :reset="setupLabData" :labTests="tests" />
            <DetailLabOrders v-if="currentData" :row-data="selectedRowData" :reset="setupLabData" />
            <Overlay
                :active="addFlyoutActive"
                :onClick="addingFlyoutActive"
            />
            <LabOrderTable
                :handle-row-click="handleRowClick"
                :loading="loadingLabs"
                :selected-row="selectedRowData"
                :updating-row="selectedRowUpdating"
                :updated-row="selectedRowHasUpdated"
                :tableRowData="currentData"
             />
        </div>
    </div>
</template>

<script>
    import Overlay from '../../commons/Overlay.vue';
    import NotificationPopup from '../../commons/NotificationPopup.vue';
    import FilterButtons from '../../commons/FilterButtons.vue';
    import LabOrderTable from './components/LabOrderTable.vue';
    import AddLabOrders from './components/AddLabOrders.vue';
    import DetailLabOrders from './components/DetailLabOrders.vue';
    import tableDataTransform from './utils/tableDataTransform';

    export default {
        name: 'LabOrders',
        components: {
            LabOrderTable,
            AddLabOrders,
            Overlay,
            DetailLabOrders,
            FilterButtons,
            NotificationPopup
        },
        data() {
            return {
                activeFilter: 0,
                selectedRowData: null,
                selectedRowUpdating: null,
                selectedRowHasUpdated: null,
                addFlyoutActive: false,
                detailFlyoutActive: false,
                addActiveModal: false,
                patientCard: null,
                loading: false,
                cache: {
                    Recommended: [],
                    Confirmed: [],
                    Shipped: [],
                    Received: [],
                    Mailed: [],
                    Processing: [],
                    Complete: []
                },
                labData: [],
                step: 1,
                currentData: [],
                notificationSymbol: '&#10003;',
                notificationMessage: '',
                notificationActive: false,
                notificationDirection: 'top-right',
                userType: null
            };
        },
        methods: {
            handleRowClick(obj, index) {
                let data;
                if (obj) {
                    data = obj.data === this.selectedRowData ? null : obj.data;
                } else {
                    data = null;
                }
                this.detailFlyoutActive = !this.detailFlyoutActive;
                if (data) {
                    this.detailFlyoutActive = true;
                    this.selectedRowData = data;
                    this.selectedRowIndex = index;
                    this.step = 1;
                    this.loading = true;
                    if (this.$root.$data.permissions !== 'patient') this.getPatientCreditCard(data.patient_user_id);
                } else {
                    this.selectedRowData = null;
                    this.selectedRowIndex = null;
                    this.detailFlyoutActive = false;
                    this.step = 1;
                    this.loading = true;
                }
            },
            handleFilter(name, index) {
                this.activeFilter = index;
                switch (name.name) {
                    case "Recommended":
                        this.currentData = this.cache.Recommended;
                        break;
                    case "Confirmed":
                        this.currentData = this.cache.Confirmed;
                        break;
                    case "Shipped":
                        this.currentData = this.cache.Shipped;
                        break;
                    case "Received":
                        this.currentData = this.cache.Received;
                        break;
                    case "Mailed":
                        this.currentData = this.cache.Mailed;
                        break;
                    case "Processing":
                        this.currentData = this.cache.Processing;
                        break;
                    case "Complete":
                        this.currentData = this.cache.Complete;
                        break;
                }
            },
            $$rowClasses(data, index) {
                return {
                    'is-selected': this.selectedRow === data,
                    'is-updating': this.updatingRow === index,
                    'has-updated': this.updatedRow === index
                };
            },
            closeFlyouts() {
                this.detailFlyoutActive = false;
                this.addFlyoutActive  = false;
                this.selectedRowData = null;
                this.selectedRowIndex = null;
                this.loading = true;
            },
            addingFlyoutActive() {
                this.detailFlyoutActive = false;
                this.addFlyoutActive = !this.addFlyoutActive;
            },
            detailsFlyoutActive() {
                if (this.selectedRowData != null) this.selectedRowData = null;
                this.addFlyoutActive = !this.addFlyoutActive;
                this.detailFlyoutActive = !this.detailFlyoutActive;
                this.loading = true;
            },
            setupLabData() {
                let global = this.$root.$data.global;
                let permissions = this.$root.$data.permissions;
                let patient = null;
                if (permissions === 'patient' && global.user && global.user.included) {
                    patient = {};
                    patient[global.user.included.id] = global.user.included;
                } else {
                    patient = global.patientLookUp;
                }
                let data = tableDataTransform(
                    global.labOrders,
                    global.labTests,
                    patient,
                    global.practitionerLookUp,
                    this.$root.$data.labTests
                );
                this.labData = data;
                let choices = {
                    0: "Recommended",
                    1: "Confirmed",
                    2: "Shipped",
                    3: "Received",
                    4: "Mailed",
                    5: "Processing",
                    6: "Complete"
                };
                data.sort((a,b) => new Date(b.data.date) - new Date(a.data.date));
                this.cache[choices['0']] = data.filter(e => e.data.completed_at == "Recommended");
                this.cache[choices['1']] = data.filter(e => e.data.completed_at == "Confirmed");
                this.cache[choices['2']] = data.filter(e => e.data.completed_at == "Shipped");
                this.cache[choices['3']] = data.filter(e => e.data.completed_at == "Received");
                this.cache[choices['4']] = data.filter(e => e.data.completed_at == "Mailed");
                this.cache[choices['5']] = data.filter(e => e.data.completed_at == "Processing");
                this.cache[choices['6']] = data.filter(e => e.data.completed_at == "Complete");
                this.currentData = data.filter(e => e.data.completed_at == "Recommended");
                if (permissions === 'patient') {
                    this.currentData = data;
                }
            },
            getLabTests() {
                this.tests = this.$root.$data.labTests;
            },
            getPatientCreditCard(userId) {
                axios.get(`${this.$root.$data.apiUrl}/users/${userId}/cards`)
                .then(response => {
                    this.patientCard = response.data.cards[response.data.cards.length - 1];
                })
                .then(() => {
                    this.loading = false;
                });
            }
        },
        computed: {
            disabledFilters() {
                return this.$root.$data.global.loadingLabOrders || this.$root.$data.global.loadingLabTests || this.selectedRowUpdating !== null;
            },
            filters() {
                return [
                    {name: `Recommended`, count: this.cache.Recommended.length},
                    {name: `Confirmed`, count: this.cache.Confirmed.length},
                    {name: `Shipped`, count: this.cache.Shipped.length},
                    {name: `Received`, count: this.cache.Received.length},
                    {name: `Mailed`, count: this.cache.Mailed.length},
                    {name: `Processing`, count: this.cache.Processing.length},
                    {name: `Complete`, count: this.cache.Complete.length}
                ];
            },
            loadingLabs() {
                const global = this.$root.$data.global;
                let permissions = this.$root.$data.permissions;
                if (permissions === 'admin') {
                    return global.loadingLabTests ||
                    global.loadingLabOrders ||
                    global.loadingPatients ||
                    global.loadingTestTypes ||
                    global.loadingPractitioners;
                } else if (permissions === 'practitioner') {
                    return global.loadingLabTests ||
                    global.loadingLabOrders ||
                    global.loadingTestTypes ||
                    global.loadingPatients;
                } else if (permissions === 'patient') {
                    return global.loadingLabTests ||
                    global.loadingLabOrders ||
                    global.loadingTestTypes ||
                    global.loadingPractitioners ||
                    global.loadingUser ||
                    global.loadingCreditCards;
                }
                return false;
            },
            labTests() {
                return this.$root.$data.labTests.length > 0;
            },
            tests() {
                return this.$root.$data.labTests;
            }
        },
        watch: {
            loadingLabs(val) {
                if (!val) {
                    this.setupLabData();
                }
            },
            labTests(val) {
                if (!val) {
                    this.getLabTests();
                }
            }
        },
        mounted() {
            this.$root.$data.global.currentPage = 'lab-orders';
            if (!global.loadingLabTests ||
                !global.loadingLabOrders ||
                !global.loadingPractitioners ||
                !global.loadingUser ||
                !global.loadingTestTypes ||
                !global.loadingCreditCards) {
                this.setupLabData();
            }
        },
        destroyed() {
            this.cache = {
                Recommended: [],
                Confirmed: [],
                Shipped: [],
                Received: [],
                Mailed: [],
                Processing: [],
                Complete: []
            };
            this.currentData = [];
            this.$root.$data.global.loadingLabTests = true;
            this.$root.$data.global.loadingLabOrders = true;
            axios.get(`${this.$root.$data.apiUrl}/lab/orders?include=patient,user,invoice`)
                .then(response => {
                    if (response.data.included) {
                        let user = response.data.included.filter(e => e.type === 'users');
                        let patient = response.data.included.filter(e => e.type === 'patients');
                        let invoices = response.data.included.filter(e => e.type === 'invoices');
                        let obj = {};
                        if (invoices.length > 0) {
                            invoices.forEach(e => {
                                obj[e.id] = e;
                            });
                        }
                        this.$root.$data.global.labOrders = response.data.data.map((e, i) => {
                            e.user = user[i];
                            e.patient = patient[i];
                            if (e.relationships.invoice) {
                                e.invoice = obj[e.relationships.invoice.data.id];
                            }
                            return e;
                        });
                    }
                    this.$root.$data.global.loadingLabOrders = false;
                });

            axios.get(`${this.$root.$data.apiUrl}/lab/tests?include=sku`)
                .then(response => {
                     let sku_ids = {};
                     if (!response.data.included) {
                         this.$root.$data.global.labTests = response.data.data;
                         this.$root.$data.global.loadingLabTests = false;
                         return;
                    }
                    response.data.included.forEach(e => {
                        sku_ids[e.id] = e;
                    });
                    this.$root.$data.global.labTests = response.data.data.map((e) => {
                        e.included = sku_ids[e.relationships.sku.data.id];
                        return e;
                    });
                    this.$root.$data.global.loadingLabTests = false;
                });

            axios.get(`${this.$root.$data.apiUrl}/lab/tests/information`)
                .then(response => {
                    response.data.data.forEach(e => {
                        this.$root.$data.labTests[e.id] = e;
                        this.$root.$data.labTests[e.id]['checked'] = false;
                    });
                })
                .then(() => {
                    this.$root.$data.global.loadingTestTypes = false;
                });
        }
    };
</script>
