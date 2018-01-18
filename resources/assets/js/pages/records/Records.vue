<template>
  <div class="main-container">

      <!-- Non-Patient -->
      <div v-if="$root.$data.permissions !== 'patient'">

        <!-- Non-Patient Step One -->
        <div v-if="step == 1" class="relative">
            <SlideIn v-if="$root.$data.global.loadingPatients" class="pa2 pa3-m">
                <LoadingSpinner class="mt3" />
                <Paragraph class="tc mt2" :size="'large'">Records loading</Paragraph>
            </SlideIn>

          <Grid v-if="!$root.$data.global.loadingPatients" :flexAt="'l'" :columns="[{ l:12 }]" :gutters="{ s:3, l:3 }">
            <div :slot="1" class="bb b--light-gray bg-white pa4 w-100">
              <form>
                <i class="font-lg pt1 fa fa-search absolute left-2"></i>
                <input v-model="search" placeholder="Search name, email or birthday..." @keydown="updateInput($event)" type="text" class="b--none f5 f3-m font-m fw1 w-100 pl4" />
              </form>
            </div>
          </Grid>

          <div class="pa2 pa3-m">

            <!-- No results -->
            <div v-if="!$root.$data.global.loadingPatients && results.length === 0 && search !== ''" class="mt6 tc">
              <i class="fa fa-ban f2 mb-3"></i>
              <Heading2>No Records Found</Heading2>
            </div>

            <!-- Results -->
            <table v-if="search !== ''" class="w-100 fs3 collapse">
              <tr v-for="patient in results" @click="selectPatient(patient)" class="patient-row">
                <td class="pt2 pb2 pl2">{{ patient.search_name }}</td>
                <td class="pt2 pb2">{{ patient.email }}</td>
                <td class="pt2 pb2 tr pr2">{{ patient.date_of_birth  || 'N/A' }}</td>
              </tr>
            </table>

          </div>

          <Modal :active="activeModal" :onClose="modalClose">
            <div class="inline-centered">
              <h1>Warning</h1>
              <p>You are about to access personal health information for client <b>{{ name }}</b>. By accessing this document you hereby agree that you have been given permission to access this private health record. Please note, all actions will be recorded in this area.</p>
              <button @click="modalClose" class="bg-gray button">Go Back</button>
              <button @click="nextStep" class="button">Yes, I agree</button>
            </div>
          </Modal>

        </div>

        <!-- Non-Patient Step Two -->
        <div v-if="step == 2">
          <div v-if="$root.$data.permissions !== 'patient'">

            <div class="content-with-flyout">

              <div class="bb b--light-gray bg-white pa4 w-100 relative">
                <!-- Search Bar -->
                <Grid :flexAt="'xxl'" :columns="[{ xxl:8 }, { xxl:4 }]">
                    <div :slot="1">
                      <form>
                        <i class="font-lg pt1 fa fa-search absolute left-2"></i>
                        <input v-model="search" placeholder="Search name, email or birthday..." @keydown="updateInput($event)" type="text" class="b--none font-xl fw1 w-100 pl4" />
                      </form>
                    </div>
                </Grid>
              </div>

              <!-- Actions -->
              <div :slot="2" class="searchbar-actions pt3 ph3">
                <Grid :flexAt="'l'" :columns="[{ l:6 }, { l:6 }, { l:12 }]" :gutters="{ s:2, l:2 }">
                  <span :slot="1" class="custom-select">
                    <select class="f3 h-100 bg-white" @change="updateMenu($event)">
                      <option v-for="menuItem in dropDownMenu">{{ menuItem }}</option>
                    </select>
                  </span>
                  <button :slot="2" @click="newRecord" class="button dib fr">New Record</button>
                  <button :slot="3" class="button flyout-toggle" @click="handleFlyoutOpen">
                    View Current Records
                  </button>
                </Grid>
              </div>

              <div class="pa2 pa3-m">
                <div v-if="page === 0">
                  <!-- Add First card here -->
                </div>

                <div v-if="page !== 0">

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

            </div>

            <!-- Flyout -->
            <Flyout class="hide-print" :active="isFlyoutActive" :on-close="null" :button="true" :header="false">

              <button class="button--close flyout-close" @click="handleFlyoutClose" data-test="close">
                  <svg><use xlink:href="#close" /></svg>
              </button>

              <!-- Info -->
              <div class="flyout-patient-info">
                <Heading2 class="dib no-border w-70">{{selectedPatient.search_name}}</Heading2>
                <img class="w3 h3 fr" :src="selectedPatient.image" />
                <a class="db" :href="'mailto:' + selectedPatient.email">{{ selectedPatient.email }}</a>
                <a class="db" :href="'tel:' + selectedPatient.phone">{{ selectedPatient.phone || 'N/A' }}</a>
              </div>

              <Spacer isBottom :size="4" />

              <!-- Details -->
              <div class="flyout-patient-info">
                <Grid :flexAt="'l'" :columns="[{ s:6 }, { s:6 }]">
                  <div :slot="1">
                    <span class="db pa1">ID: <b>#{{ selectedPatient.id }}</b></span>
                    <span class="db pa1">Joined: <b>{{ selectedPatient.created_at }}</b></span>
                    <span class="db pa1">DOB: <b>{{ selectedPatient.date_of_birth || 'N/A' }}</b></span>
                  </div>
                  <div :slot="2">
                    <span class="db pa1">City: <b>{{ selectedPatient.city || 'N/A' }}</b></span>
                    <span class="db pa1">State: <b>{{ selectedPatient.state || 'N/A' }}</b></span>
                  </div>
                </Grid>
              </div>

              <Spacer isBottom :size="4" />

              <!-- Timeline -->
              <div>
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

      <!-- Patient -->
      <div v-if="$root.$data.permissions === 'patient'">
        <div class="content-with-flyout">
          <!-- Loading state -->
          <Grid :flexAt="'l'" :columns="[{ l:12 }]" :gutters="{ s:3, l:3 }">
            <div :slot="1" v-if="patientLoading && !selectedUserPatient" class="pa2 pa3-m">
              <LoadingSpinner class="mt3" />
              <Paragraph class="tc mt2" :size="'large'">Your records are loading...</Paragraph>
            </div>
          </Grid>

          <div v-if="once && selectedUserPatient">
            {{ getTimelineData() }}
          </div>

          <div :slot="2" class="searchbar-actions pa4 mt4">
            <Grid :flexAt="'l'" :columns="[{ l:12 }]" :gutters="{ s:2, l:0 }">
              <button :slot="1" class="button flyout-toggle" @click="handleFlyoutOpen">
                View Current Records
              </button>
            </Grid>
          </div>

          <div v-if="selectedUserPatient">
            <div class="pa2 pa3-m">
              <div v-if="page === 0">
                <!-- Add First card here -->
              </div>

              <div v-if="page !== 0">

                <div v-if="page === 1">
                  <Treatment :patient="selectedUserPatient" />
                </div>
                <div v-if="page === 2">
                  <LabResults :patient="selectedUserPatient" />
                </div>
                <div v-if="page === 3">
                  <Prescription :patient="selectedUserPatient" />
                </div>
                <div v-if="page === 4">
                  <Attachment :patient="selectedUserPatient" />
                </div>
                <div v-if="page === 5">
                  <Intake :patient="selectedUserPatient" />
                </div>

              </div>
            </div>
          </div>

          <div class="hide-print">
            <Flyout class="hide-print" :active="isFlyoutActive" :on-close="null" :button="true" :header="false">

              <button class="button--close flyout-close" @click="handleFlyoutClose" data-test="close">
                  <svg><use xlink:href="#close" /></svg>
              </button>

              <!-- Info -->
              <div class="flyout-patient-info">
                <Heading2 class="dib no-border w-70">{{selectedUserPatient.search_name}}</Heading2>
                <img class="w3 h3 fr" :src="selectedUserPatient.image" />
                <a class="db" :href="'mailto:' + selectedUserPatient.email">{{ selectedUserPatient.email }}</a>
                <a class="db" :href="'tel:' + selectedUserPatient.phone">{{ selectedUserPatient.phone }}</a>
              </div>

              <Spacer isBottom :size="4" />

              <!-- Details -->
              <div class="flyout-patient-info">
                <Grid :flexAt="'l'" :columns="[{ s:6 }, { s:6 }]">
                  <div :slot="1">
                    <span class="db pa1">ID: <b>#{{ selectedUserPatient.id }}</b></span>
                    <span class="db pa1">Joined: <b>{{ selectedUserPatient.created_at }}</b></span>
                    <span class="db pa1">DOB: <b>{{ selectedUserPatient.date_of_birth }}</b></span>
                  </div>
                  <div :slot="2">
                    <span class="db pa1">City: <b>{{ selectedUserPatient.city }}</b></span>
                    <span class="db pa1">State: <b>{{ selectedUserPatient.state }}</b></span>
                  </div>
                </Grid>
              </div>

              <Spacer isBottom :size="4" />

              <!-- Timeline -->
              <div>
                <Timeline
                :index="index"
                :items="timelineData"
                :emptyMessage="`No records for this patient`"
                :loading="loading">
                <div class="w-100 new-attachment">
                    <button class="button" @click="newAttachment">
                        New Attachment
                    </button>
                </div>
                </Timeline>
              </div>
            </Flyout>
          </div>

          <NotificationPopup
          :active="notificationActive"
          :comes-from="notificationDirection"
          :symbol="notificationSymbol"
          :text="notificationMessage"
          />

        </div>
      </div>

  </div>
</template>

<script>
import { LoadingSpinner } from 'feedback';
import { Paragraph, Heading2, Heading3 } from 'typography';
import { Card, CardContent, Grid, PageHeader, PageContainer, SlideIn, Spacer } from 'layout';

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
import { capitalize, startCase } from 'lodash';
import moment from 'moment';
export default {
    name: 'Records',
    components: {
        Paragraph,
        Heading2,
        LoadingSpinner,
        Card,
        CardContent,
        Grid,
        PageContainer,
        PageHeader,
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
        NotificationPopup,
        Heading3,
        SlideIn,
        Spacer
    },
    data() {
        return {
            step: 1,
            search: '',
            selectedPatient: null,
            activeModal: false,
            isFlyoutActive: false,
            name: '',
            showing: [],
            page: 0,
            index: null,
            timeline: [],
            once: true,
            loading: true,
            news: true,
            patientLoading: Laravel.user.user_type === 'patient' && !this.selectedUserPatient,
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
                case "Treatment Plan":
                    this.menuIndex = 1;
                    break;
                default:
                    break;
            }
        },
        newAttachment() {
            this.news = true;
            this.menuIndex = 4;
            this.setIndex(null);
            this.setPage(4);
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
            this.selectedPatient.created_at = moment.tz(patient.created_at.date, patient.created_at.timezone).tz(this.$root.$data.timezone).format("MM/DD/YY");
            this.name = patient.search_name;
            this.activeModal = true;
        },
        nextStep() {
            this.step = 2;
            this.search = '';
            this.once = true;
            this.getTimelineData();
        },
        modalClose() {
            this.activeModal = false;
        },
        handleFlyoutClose() {
            this.isFlyoutActive = false;
        },
        handleFlyoutOpen() {
            this.isFlyoutActive = true;
        },
        getTimelineData() {
            axios.get(`${this.$root.$data.apiUrl}/patients/${this.selectedUserPatient && this.selectedUserPatient.id ? this.selectedUserPatient.id : this.selectedPatient.id}?include=attachments,soap_notes,intake,prescriptions,lab_orders.lab_tests.results`)
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
                            e.type === 'lab_test' ?
                                this.lab_tests[e.id] = e :
                            e.type === 'lab_order' ?
                                this.lab_orders[e.id] = e :
                            null;
                            let object = {};
                            object.doctor = e.attributes.doctor_name || "No Doctor";
                            object.original_date = null;
                            if (e.attributes && e.attributes.created_at && e.attributes.created_at.date) {
                                object.date = moment.tz(e.attributes.created_at.date, e.attributes.created_at.timezone).tz(this.$root.$data.timezone).format('dddd, MMM Do YYYY');
                                object.original_date = e.attributes.created_at.date;
                            }
                            object.type = e.type.split('_').map(ele => capitalize(ele)).join(' ');
                            if (e.type === 'soap_note') {
                                object.type = this.$root.$data.permissions === 'patient' ? 'Treatment Plan' : 'SOAP Note';
                            }
                            object.id = e.id;
                            object.data = e;
                            this.timeline.push(object);
                        });
                        this.timeline = this.timeline.filter(e => e.type !== 'Lab Order').filter(e => e.type !== 'Lab Test');
                        this.timeline = this.timeline.sort((a, b) => new Date(b.original_date) - new Date(a.original_date));
                    }
                    this.loading = false;
                    this.once = false;
                });
        },
        setProps(data) {
            this.news = false;
            this.propData = data;
        },
        setPatientLoading(bool) {
            this.patientLoading = bool;
        },
        reusablePatientFiltering() {
            let patientData = this.$root.$data.global.user.included.attributes;
            let patientUserData = this.$root.$data.global.user.attributes;
            let patientUserId = this.$root.$data.global.user.id;
            let patientId = this.$root.$data.global.user.included.id;
            let object = {
                address_1: patientUserData.address_1 || 'N/A',
                address_2: patientUserData.address_2 || 'N/A',
                city: patientUserData.city || 'N/A',
                date_of_birth:  patientData.birthdate && patientData.birthdate.date ? moment(patientData.birthdate.date).format("MM/DD/YY") : 'N/A',
                email: patientUserData.email,
                has_a_card: patientUserData.has_a_card,
                id: patientId,
                name: `${patientUserData.last_name}, ${patientUserData.first_name}`,
                phone: patientUserData.phone || 'N/A',
                search_name: `${patientUserData.first_name} ${patientUserData.last_name}`,
                state: patientUserData.state || 'N/A',
                user_id: patientUserId,
                zip: patientUserData.zip || 'N/A',
                image: patientUserData.image_url,
                created_at: moment.tz(patientUserData.created_at.date, patientUserData.created_at.timezone).tz(this.$root.$data.timezone).format("MM/DD/YY")
            };
            this.patientLoading = false;
            return object;
        },
        resultFiltering() {
            let array = this.$root.$data.global.patients;
            let matcher = new RegExp(this.search, 'ig');
            return array.filter(ele => {
                return matcher.test(ele.search_name) ||
                            matcher.test(ele.email) ||
                            matcher.test(ele.date_of_birth);
                });
        }
    },
    computed: {
        results() {
            if (this.$root.$data.permissions !== 'patient') {
                return this.resultFiltering();
            } else {
                return [];
            }
        },
        dropDownMenu() {
            return this.$root.$data.permissions !== 'patient' ? [
                'SOAP Note',
                'Prescription',
                'Lab Results',
                'Attachment'
            ] : [
                'Attachment'
            ];
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
                        this.setPage(1);
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
                    let reg = new RegExp('Lab Test', 'ig');
                    let regex = new RegExp('Result', 'ig');
                    let aregex = new RegExp('Attachment', 'ig');
                    if (regex.test(e.type)) {
                        if (reg.test(e.type)) {
                            e.type = e.type.replace(reg, this.$root.$data.labTests[this.lab_tests[e.data.attributes.lab_test_id].attributes.sku_id].attributes.name);
                        }
                        e.onClick = onClickFunctions['Lab Test Result'].bind(this, e.data, i);
                    } else if (aregex.test(e.type)) {
                        if (e.type === 'Attachment') {
                            e.type = startCase(this.attachments[e.id].attributes.name) + ' ' + e.type;
                        }
                        e.onClick = onClickFunctions['Attachment'].bind(this, e.data, i);
                    } else {
                        e.onClick = onClickFunctions[e.type].bind(this, e.data, i);
                    }
                    return e;
                });
                return arrays;
            },
            selectedUserPatient() {
                if (this.$root.$data.permissions !== 'patient') {
                    return null;
                } else {
                    if (this.$root.$data.global.user && this.$root.$data.global.user.attributes) {
                        return this.reusablePatientFiltering();
                    } else {
                        return false;
                    }
                }
            }
        },
        watch: {
            results() {
                if (this.search !== '' && this.$root.$data.permissions !== 'patient') {
                this.step = 1;
                this.activeModal = false;
                this.selectedPatient = null;
                return this.resultFiltering();
            }
        },
        selectedUserPatient(val) {
            if (val === false && this.$root.$data.permissions !== 'patient') {
                return this.reusablePatientFiltering();
            } else {
                return null;
            }
        }
    },
    mounted() {
        this.$root.$data.global.currentPage = 'records';
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .new-attachment {
        display: flex;
        justify-content: center;
        margin: 25px 0;
    }

    .main-container {
      flex-grow: 1;
      transition: left 200ms ease-in-out, margin-left 200ms ease-in-out;

      .menu-open & {
        left: 150px;
      }

      @include query(xl) {
        margin-left: 150px;
        left: 0px !important;
      }
    }
    .flyout-patient-info {
      border-bottom: 1px solid $color-gray-5;
      padding-bottom: 2rem;
    }
    .patient-row{
      cursor: pointer;

      &:hover {
        background-color: $color-gray-5;
      }
    }
    .content-with-flyout {
      @include query(lg) {
        padding-right: 350px;
      }
      @include query(xxl) {
        position: relative;
      }
    }
    .custom-select {
      margin-top: 0;
    }
    .searchbar-actions {
      @include query(xxl) {
        background: $color-white;
        min-width: 400px;
        padding: 0 0 0 15px;
        position: absolute;
        right: 370px;
        top: 23px;
      }
    }
    .flyout-toggle,
    .flyout-close {
      @include query(lg) {
        display: none;
      }
    }
    .flyout {
      max-width: none;
      right: -100%;

      &.isactive {
        right: 0;
      }

      @include query(lg) {
        max-width: 25em;
        right: 0!important;
      }
    }
</style>
