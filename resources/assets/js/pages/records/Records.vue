<template>
    <div class="main-container">
        <UserNav />

        <div v-if="$root.$data.permissions !== 'patient'">
            <div v-if="step == 1">
                <div class="main-content">
                    <div class="card records-loading" v-if="$root.$data.global.loadingPatients">
                        <p><i>Your records are loading...</i></p>
                    </div>
                    <form v-if="!$root.$data.global.loadingPatients" class="form full-width">
                        <i class="fa fa-search search-icon"></i>
                        <input v-model="search" placeholder="Search by name, email or date of birth..." @keydown="updateInput($event)" type="text" class="search-bar" />
                    </form>
                    <div v-if="!$root.$data.global.loadingPatients && results.length === 0 && search !== ''" class="inline-centered">
                        <img class="inline-centered full-width height500" src="images/if_ic_library_514023.svg" alt="">
                        <h1 class="no-records-label">No records found.</h1>
                    </div>
                    <div v-if="!$root.$data.global.loadingPatients && search === ''" class="inline-centered">
                        <i class="inline-centered fa fa-search search-div-icon" />
                        <h1 class="search-records-label">Search for records.</h1>
                    </div>
                    <Modal :active="activeModal" :onClose="modalClose">
                        <div class="inline-centered">
                            <h1>Warning</h1>
                            <p>You are about to access personal health information for client <b>{{ name }}</b>. By accessing this document you hereby agree that you have been given permission to access this private health record. Please note, all actions will be recorded in this area.</p>
                            <button @click="modalClose" class="button grey-background">Go Back</button>
                            <button @click="nextStep" class="button">Yes, I agree</button>
                        </div>
                    </Modal>
                    <div class="container container-backoffice" v-if="search !== ''">
                        <div v-for="patient in results" @click="selectPatient(patient)" class="results">
                            <div class="spacing">{{ patient.search_name }}</div>
                            <div class="spacing">{{ patient.email }}</div>
                            <div class="spacing">{{ patient.date_of_birth }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="step == 2" class="main-content">
                <div v-if="$root.$data.permissions !== 'patient'">
                    <div class="form">
                        <i class="fa fa-search search-icon"></i>
                        <input v-model="search" placeholder="Type anything to go back to the search..." @keydown="updateInput($event)" type="text" class="search-bar" />
                    </div>
                    <div class="records-button-container">
                        <span class="custom-select soat-button">
                            <select @change="updateMenu($event)">
                                <option v-for="menuItem in dropDownMenu">{{ menuItem }}</option>
                            </select>
                        </span>
                        <button @click="newRecord" class="button records-button">New Record</button>
                    </div>
                    <div class="auto-height">
                        <div v-if="page === 0">
                            <img class="inline-centered height500" src="images/if_ic_library_514023.svg" style="width: 70%;" alt="">
                        </div>
                        <div class="card width70" v-if="page !== 0">
                            <div class="card-heading-container height65">
                                <h2 class="left-records-label">
                                    {{ page === 1 ? `${news ? 'New ' : ''}SOAP Note` : null }}
                                    {{ page === 2 ? `${news ? 'New ' : ''}Lab Results` : null }}
                                    {{ page === 3 ? `${news ? 'New ' : ''}Prescription` : null }}
                                    {{ page === 4 ? `${news ? 'New ' : ''}Attachment` : null }}
                                    {{ page === 5 ? `Intake Form` : null }}
                                    {{ page === 6 ? `Treatment Plan` : null }}
                                </h2>
                                <h2 class="search-name-label">
                                    {{ selectedPatient.search_name }}
                                </h2>
                            </div>

                            <div v-if="page === 1">
                                <SoapNote :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 2">
                                <LabResults :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 3">
                                <Prescription :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 4">
                                <Attachment :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 5">
                                <Intake :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 6">
                                <Treatment :patient="selectedPatient" />
                            </div>

                        </div>
                    </div>
                    <Flyout :active="true" :onClose="null" :button="true" :header="true" :heading="selectedPatient.search_name">
                        <a class="flyout-links" :href="'mailto:' + selectedPatient.email">{{ selectedPatient.email }}</a>
                        <a class="flyout-links" :href="'tel:' + selectedPatient.phone">{{ selectedPatient.phone }}</a>
                        <div class="records-image" :style="`background-image: url(${selectedPatient.image});`" />
                        <div class="records-divider" />
                        <div class="input__container mid-section-flyout">
                            <div class="half-left">
                                <span class="full-left">ID: <b>#{{ selectedPatient.id }}</b></span>
                                <span class="full-left">Joined: <b>{{ selectedPatient.created_at }}</b></span>
                                <span class="full-left">DOB: <b>{{ selectedPatient.date_of_birth }}</b></span>
                            </div>
                            <div class="half-left">
                                <span class="full-left">City: <b>{{ selectedPatient.city }}</b></span>
                                <span class="full-left">State: <b>{{ selectedPatient.state }}</b></span>
                            </div>
                        </div>
                        <div class="input__container">
                            <Timeline 
                                :index="index" 
                                :items="timelineData" 
                                :emptyMessage="`No records for this patient`"
                                :loading="loading" />
                        </div>
                    </Flyout>

                    <NotificationPopup
                        :active="notificationActive"
                        :comes-from="notificationDirection"
                        :symbol="notificationSymbol"
                        :text="notificationMessage"
                    />
                    
                </div>
            </div>
        </div>

        <div v-if="$root.$data.permissions === 'patient'">
            <div class="main-content">
                <div>
                    <div class="auto-height">
                        <div v-if="page === 0">
                            <img class="inline-centered height500" src="images/if_ic_library_514023.svg" style="width: 70%;" alt="">
                        </div>
                        <div class="card width70" v-if="page !== 0">
                            <div class="card-heading-container height65">
                                <h2 class="left-records-label">
                                    {{ page === 1 ? `${news ? 'New ' : ''}SOAP Note` : null }}
                                    {{ page === 2 ? `${news ? 'New ' : ''}Lab Results` : null }}
                                    {{ page === 3 ? `${news ? 'New ' : ''}Prescription` : null }}
                                    {{ page === 4 ? `${news ? 'New ' : ''}Attachment` : null }}
                                    {{ page === 5 ? `Intake Form` : null }}
                                    {{ page === 6 ? `Treatment Plan` : null }}
                                </h2>
                                <h2 class="search-name-label">
                                    {{ selectedPatient.search_name }}
                                </h2>
                            </div>

                            <div v-if="page === 1">
                                <SoapNote :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 2">
                                <LabResults :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 3">
                                <Prescription :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 4">
                                <Attachment :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 5">
                                <Intake :patient="selectedPatient" />
                            </div>
                            <div v-if="page === 6">
                                <Treatment :patient="selectedPatient" />
                            </div>

                        </div>
                    </div>
                    <Flyout :active="true" :onClose="null" :button="true" :header="true" :heading="selectedPatient.search_name">
                        <a class="flyout-links" :href="'mailto:' + selectedPatient.email">{{ selectedPatient.email }}</a>
                        <a class="flyout-links" :href="'tel:' + selectedPatient.phone">{{ selectedPatient.phone }}</a>
                        <div class="records-image" :style="`background-image: url(${selectedPatient.image});`" />
                        <div class="records-divider" />
                        <div class="input__container mid-section-flyout">
                            <div class="half-left">
                                <span class="full-left">ID: <b>#{{ selectedPatient.id }}</b></span>
                                <span class="full-left">Joined: <b>{{ selectedPatient.created_at }}</b></span>
                                <span class="full-left">DOB: <b>{{ selectedPatient.date_of_birth }}</b></span>
                            </div>
                            <div class="half-left">
                                <span class="full-left">City: <b>{{ selectedPatient.city }}</b></span>
                                <span class="full-left">State: <b>{{ selectedPatient.state }}</b></span>
                            </div>
                        </div>
                        <div class="input__container">
                            <Timeline 
                                :index="index" 
                                :items="timelineData" 
                                :emptyMessage="`No records for this patient`"
                                :loading="loading" />
                        </div>
                    </Flyout>

                    <NotificationPopup
                        :active="notificationActive"
                        :comes-from="notificationDirection"
                        :symbol="notificationSymbol"
                        :text="notificationMessage"
                    />
                    
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue';
import Modal from '../../commons/Modal.vue';
import Flyout from '../../commons/Flyout.vue';
import Timeline from '../../commons/Timeline.vue';
import SoapNote from './components/SoapNote.vue';
import LabResults from './components/LabResults.vue';
import Prescription from './components/Prescription.vue';
import Attachment from './components/Attachment.vue';
import Intake from './components/Intake.vue';
import Treatment from './components/Treatment.vue';
import NotificationPopup from '../../commons/NotificationPopup.vue';
import axios from 'axios';
import { capitalize } from 'lodash';
import moment from 'moment';
export default {
    name: 'Records',
    components: {
        UserNav,
        Modal,
        Flyout,
        Timeline,
        SoapNote,
        LabResults,
        Prescription,
        Attachment,
        Intake,
        Treatment,
        NotificationPopup
    },
    data() {
        return {
            step: 1,
            search: '',
            selectedPatient: null,
            activeModal: false,
            name: '',
            showing: [],
            page: 0,
            index: null,
            timeline: [],
            loading: true,
            news: true,
            soap_notes: {},
            attachments: {},
            prescriptions: {},
            lab_tests: {},
            lab_orders: {},
            lab_test_results: {},
            intakes: {},
            propData: {},
            notificationSymbol: '&#10003;',
            notificationMessage: '',
            notificationActive: false,
            notificationDirection: 'top-right',
            dropDownMenu: [
                'SOAP Note', 
                'Prescription', 
                'Lab Results', 
                'Attachment' 
            ],
            menuIndex: 1
        };
    },
    methods: {
        updateMenu(e) {
            switch (e.target.value) {
                case "SOAP Note":
                    this.menuIndex = 1;
                    break;
                case "Lab Results":
                    this.menuIndex = 2;
                    break;
                case "Prescription":
                    this.menuIndex = 3;
                    break;
                case "Attachment":
                    this.menuIndex = 4;
                    break;
                default:
                    break;
            }
        },
        newRecord() {
            this.news = true;
            this.setIndex(null);
            this.setPage(this.menuIndex);
        },
        updateInput(e) {
            this.step = 1;
            this.page = 0;
            this.setIndex(null);
            this.activeModal = false;
            this.search = e.target.value;
            this.loading = true;
            this.soap_notes = {};
            this.attachments = {};
            this.prescriptions = {};
            this.lab_tests = {};
            this.lab_orders = {};
            this.lab_test_results = {};
        },
        setPage(page) {
            this.page = page;
        },
        setIndex(idx) {
            this.index = idx;
        },
        selectPatient(patient) {
            this.selectedPatient = patient;
            this.name = patient.search_name;
            this.activeModal = true;
        },
        nextStep() {
            this.step = 2;
            this.search = '';
            this.getTimelineData();
        },
        modalClose() {
            this.activeModal = false;
        },
        getTimelineData() {
            axios.get(`${this.$root.$data.apiUrl}/patients/${this.selectedPatient.id}?include=attachments,soap_notes,intake,prescriptions,lab_orders.lab_tests.results`)
                .then(response => {
                    this.timeline = [];
                    if (response.data.included) {
                        response.data.included.forEach((e) => {
                            if (e.type === 'lab_tests') {
                                e.included = this.$root.$data.labTests[e.attributes.sku_id];
                            }
                            e.type === 'soap_note' ? 
                                this.soap_notes[e.id] = e :
                            e.type === 'attachment' ?
                                this.attachments[e.id] = e :
                            e.type === 'prescription' ?
                                this.prescriptions[e.id] = e :
                            e.type === 'intake' ?
                                this.intakes[e.id] = e :
                            e.type === 'lab_test_result' ?
                                this.lab_test_results[e.id] = e :
                            e.type === 'lab_tests' ?
                                this.lab_tests[e.id] = e :
                            e.type === 'lab_order' ?
                                this.lab_orders[e.id] = e :
                            null;
                            let object = {};
                            object.doctor = e.attributes.doctor_name || "No Doctor";
                            object.original_date = null;
                            if (e.attributes && e.attributes.created_at && e.attributes.created_at.date) {
                                object.date = moment(e.attributes.created_at.date).format('dddd, MMM Do YYYY');
                                object.original_date = e.attributes.created_at.date;
                            }
                            object.type = e.type.split('_').map(ele => capitalize(ele)).join(' ');
                            if (e.type === 'soap_note') {
                                object.type = 'SOAP Note';
                            }
                            object.id = e.id;
                            object.data = e;
                            this.timeline.push(object);
                        });
                        this.timeline = this.timeline.filter(e => e.type !== 'Lab Order').filter(e => e.type !== 'Lab Tests');
                        this.timeline = this.timeline.sort((a, b) => new Date(b.original_date) - new Date(a.original_date));
                    }
                    this.loading = false;
                });
        },
        setProps(data) {
            this.news = false;
            this.propData = data;
        }
    },
    computed: {
        results() {
            let array = this.$root.$data.global.patients;
            let matcher = new RegExp(this.search, 'ig');
            return array.filter(ele => {
            return matcher.test(ele.search_name) ||
                        matcher.test(ele.email) ||
                        matcher.test(ele.date_of_birth);
            });
        },
        timelineData() {
                let onClickFunctions = {
                    'Intake': (data, index) => {
                        this.setIndex(index);
                        this.setPage(5);
                        this.setProps(data);
                    },
                    'Lab Test Result': (data, index) => {
                        this.setIndex(index);
                        this.setPage(2);
                        this.setProps(data);
                    },
                    'Treatment Plan': (data, index) => {
                        this.setIndex(index);
                        this.setPage(6);
                        this.setProps(data);
                    },
                    'Prescription': (data, index) => {
                        this.setIndex(index);
                        this.setPage(3);
                        this.setProps(data);
                    },
                    'Attachment': (data, index) => {
                        this.setIndex(index);
                        this.setPage(4);
                        this.setProps(data);
                    },
                    'SOAP Note': (data, index) => {
                        this.setIndex(index);
                        this.setPage(1);
                        this.setProps(data);
                    }
                };
                let arrays = this.timeline;
                arrays.map((e, i)=> {
                    e.onClick = onClickFunctions[e.type].bind(this, e.data, i);
                    return e;
                });
                return arrays;
            }
        },
        watch: {
            results() {
                if (this.search !== '') {
                this.step = 1;
                this.activeModal = false;
                this.selectedPatient = null;
                let array = this.$root.$data.global.patients;
                let matcher = new RegExp(this.search, 'ig');
                return array.filter(ele => {
                    return matcher.test(ele.search_name) ||
                                matcher.test(ele.email) ||
                                matcher.test(ele.date_of_birth);
                });
            }
        }
    },
    mounted() {
        this.$root.$data.global.currentPage = 'records';
    }
};
</script>
