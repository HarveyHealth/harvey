<template>
  <Flyout
    :class="$parent.addActiveModal && 'with-active-modal'"
    :active="$parent.addFlyoutActive"
    :heading="flyoutHeading"
    :on-close="handleFlyoutClose"
  >
    <div class="input__container">
        <label class="input__label" for="patient_name">Client</label>
        <autocomplete
            anchor="search_name"
            label=false
            url=true
            placeholder="Search name, email or birthday..."
            :debounce="500"
            :onShouldGetData="getData"
            :on-select="handlePatientSelect"
        >
        </autocomplete>
    </div>
    <div class="input__container">
      <label class="input__label" for="patient_name">Doctor</label>
      <span class="custom-select">
          <select @change="updateDoctor($event)">
              <option v-for="(doctor, key) in doctorList" :data-id="doctor.id" :selected="doctor.id === selectedDoctor">{{ doctor.name }}</option>
          </select>
      </span>
    </div>
    <div class="input__container">
      <label class="input__label" for="patient_name">Lab Tests</label>
      <div v-for="(test, index) in testNameList" :class="{highlightCheckbox: test.checked}" class="inventory-left custom-padding">
          <label :class="{highlightText: test.checked}" class="radio--text">
            <input :checked="test.checked" @click="updateTestSelection(test, index)" class="form-radio" type="checkbox">
            {{ test.attributes.name }}
            </input>
          </label>
      </div>
    </div>
    <div class="button-wrapper">
        <button class="button"
        @click="openModal()"
        :disabled="!selectedClient || !selectedDoctor || !selectedTests.length">Create Lab Order</button>
    </div>
    <Modal
      :active="$parent.addActiveModal"
      :onClose="modalClose"
      class="modal-wrapper"
    >
      <div class="card-content-wrap">
        <div class="inline-centered">
          <h1 class="header-xlarge">
            <span class="text">Create Lab Order</span>
          </h1>
          <p>Are you sure you want to create a new lab order recommedation for client <b>{{ selectedClientName }}</b>, on behalf of <b>{{ selectedDoctorName }}</b>?</p>
          <table border="0" cellpadding="0" cellspacing="0" class="modal-table inline-left">
            <tr v-for="test in selectedTests">
              <td width="25%"><strong>{{ test.attributes.lab_name }}</strong></td>
              <td width="25%">{{ test.attributes.name }}</td>
              <td width="25%" class="color-good">${{ test.attributes.price }}</td>
            </tr>
          </table>
          <div class="button-wrapper">
            <button class="button button--cancel" @click="modalClose">Cancel</button>
            <button class="button" @click="createLabOrder">Yes, Confirm</button>
          </div>
        </div>
        <div class="button-wrapper">
            <button class="button"
                @click="openModal()"
                :disabled="!selectedClient || !selectedDoctor || !selectedTests.length"
            >
                Create Lab Order
            </button>
        </div>

        <Modal
            :active="$parent.addActiveModal"
            :onClose="modalClose"
            class="modal-wrapper"
        >
            <div class="card-content-wrap">
                <div class="inline-centered">
                    <h1 class="header-xlarge">
                        <span class="text">Create Lab Order</span>
                    </h1>
                    <p>Are you sure you want to create a new lab order recommedation for client <b>{{ selectedClientName }}</b>, on behalf of <b>{{ selectedDoctorName }}</b>?</p>
                    <table border="0" cellpadding="0" cellspacing="0" class="modal-table inline-left">
                        <tr v-for="test in selectedTests">
                            <td width="25%"><strong>{{ test.attributes.lab_name }}</strong></td>
                            <td width="25%">{{ test.attributes.name }}</td>
                            <td width="25%" class="color-good">${{ test.attributes.price }}</td>
                        </tr>
                    </table>
                    <div class="button-wrapper">
                        <button class="button button--cancel" @click="modalClose">Cancel</button>
                        <button class="button" @click="createLabOrder">Yes, Confirm</button>
                    </div>
                </div>
            </div>
        </Modal>
    </Flyout>
</template>

<script>
import Flyout from '../../../commons/Flyout.vue';
import Modal from '../../../commons/Modal.vue';
import SelectOptions from '../../../commons/SelectOptions.vue';
import axios from 'axios';
import _ from 'lodash';
import Autocomplete from '../../../commons/Autocomplete.vue';


export default {
    props: {
        reset: Function,
        labTests: Object
    },
    name: 'AddLabOrders',
    components: {
        Flyout,
        Modal,
        SelectOptions,
        Autocomplete
    },
    data() {
        return {
            activeModal: false,
            selectedDoctor: '0',
            selectedClient: '0',
            step: 1,
            masterTracking: '',
            address1: '',
            address2: '',
            city: '',
            zip: '',
            state: '',
            resetting: false,
            selectedTests: [],
            shippingCodes: {},
            prevDoctor: '',
            prevClient: '',
            testNamesInList: [],
            selectedClientName: '',
            selectedDoctorName: '',
            doctorList: this.$root.$data.global.selfPractitionerInfo != null ? [this.$root.$data.global.selfPractitionerInfo] : [{id: '0', name: ''}].concat(this.$root.$data.global.practitioners),
            clientList: [{id: '0', name: ''}].concat(this.$root.$data.global.patients)
        };
    },
    methods: {
        getData(value){
            return new Promise((resolve) => {
                if (value != ""){
                    this.$root.requestPatients(value,(patients)=>{
                        resolve(patients);
                    });
                }
                else {
                    resolve([]);
                }
            });
        },

        handlePatientSelect(obj) {
            this.resetting = false;
            this.selectedClient = obj.id;
            this.selectedClientName = this.formatName(obj.search_name);
        },

        modalClose() {
            this.$parent.addActiveModal = false;
            this.selectedClient = '0';
            if (this.$root.$data.permissions !== 'practitioner') {
                this.selectedDoctor = '0';
            }
        },

        openModal() {
            this.$parent.addActiveModal = true;
        },

        updateTestSelection(test, index) {
            this.testNameList[index].checked = !this.testNameList[index].checked;
            if (this.testNameList[index].checked) {
                this.selectedTests.push(test);
            } else {
                _.pull(this.selectedTests, test);
            }
        },

        formatName(str) {
            return str.split(', ').reverse().join(' ');
        },

        updateDoctor(e) {
            this.resetting = false;
            this.selectedDoctor = e.target.children[e.target.selectedIndex].dataset.id;
            this.selectedDoctorName = e.target.value;
        },

        handleFlyoutClose() {
            this.$parent.addFlyoutActive = !this.$parent.addFlyoutActive;
            this.$parent.addActiveModal = false;
            this.selectedClient = '0';
            if (this.$root.$data.permissions !== 'practitioner') {
                this.selectedDoctor = '0';
            }
        },

        createLabOrder() {
            this.selectedTests.map(e => {
                e.shipping_code = this.shippingCodes[e.id];
                return e;
            });

            let data =  {
                practitioner_id: this.selectedDoctor,
                patient_id: this.selectedClient
            };

            axios.post(`${this.$root.$data.apiUrl}/lab/orders`, data).then(response => {
                this.selectedTests.forEach((e)=> {
                    axios.post(`${this.$root.$data.apiUrl}/lab/tests`, {
                        lab_order_id: Number(response.data.data.id),
                        sku_id: Number(e.attributes.sku_id),
                        shipment_code: this.shippingCodes[e.id]
                    });
                });

                this.selectedClient = '';
                this.step = 1;
                this.masterTracking = '';
                this.address1 = '';
                this.selectedDoctor = '';
                this.address2 = '';
                this.city = '';
                this.zip = '';
                this.state = '';
                this.selectedTests = [];
                this.shippingCodes = {};
                this.prevDoctor = '';
                this.prevClient = '';
                this.doctorList = [''].concat(this.$root.$data.global.practitioners);
                this.clientList = [''].concat(this.$root.$data.global.patients);

                Object.keys(this.$props.labTests)
                    .map(test => this.$props.labTests[test].checked = false);

                this.$parent.notificationMessage = "Successfully added!";
                this.$parent.notificationActive = true;

                setTimeout(() => this.$parent.notificationActive = false, 3000);

                axios.get(`${this.$root.$data.apiUrl}/lab/orders?include=patient,user,invoice`).then(response => {
                    this.$root.$data.global.labOrders = response.data.data.map((e, i) => {
                        e['included'] = response.data.included[i];
                        return e;
                    });
                    this.$root.$data.global.loadingLabOrders = false;
                    axios.get(`${this.$root.$data.apiUrl}/lab/tests?include=sku`).then(response => {
                        let sku_ids = {};
                        response.data.included.forEach(e => {
                            sku_ids[e.id] = e;
                        });
                        this.$root.$data.global.labTests = response.data.data.map((e) => {
                            e.included = sku_ids[e.relationships.sku.data.id];
                            return e;
                        });
                        this.$root.$data.global.loadingLabTests = false;
                        this.$props.reset();
                    });
                });
            })
            .then(() => {
                this.handleFlyoutClose();
                this.modalClose();
            });
        }
    },
    computed: {
        flyoutHeading() {
            if (this.step == 1) return "New Lab Order";
            if (this.step == 2) return "Enter Tracking #s";
        },
        testNameList() {
            return Object.keys(this.$props.labTests)
                .map(key => this.$props.labTests[key])
                .sort((a, b) => a.id - b.id);
        }
    },
    watch: {
        testNameList(val) {
            if (val) {
                return Object.keys(this.$props.labTests)
                    .map(key => this.$props.labTests[key])
                    .sort((a, b) => a.id - b.id);
            }
        }
    },
    mounted() {
        let selfPractitioner = this.$root.$data.global.selfPractitionerInfo;
        if (selfPractitioner) {
            this.selectedDoctor = selfPractitioner.id;
            this.selectedDoctorName = selfPractitioner.name;
        }
    }
};
</script>
