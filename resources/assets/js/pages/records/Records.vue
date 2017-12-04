<template>
  <PageContainer>

          <!-- Non-Paitent -->
          <div v-if="$root.$data.permissions !== 'patient'">

            <!-- Non-Patient Step One -->
            <div v-if="step == 1">
              <Grid :flexAt="'l'" :columns="[{l: '1of1'}]" :gutters="{ s:3, l:3 }" alignTo="middle">
                <div :slot="1" v-if="$root.$data.global.loadingPatients" class="pa2 pa3-m">
                  <LoadingSpinner class="mt3" />
                  <Paragraph class="tc mt2" :size="'large'">Records loading</Paragraph>
                </div>
              </Grid>

              <Grid :flexAt="'l'" :columns="[{l: '1of1'}]" :gutters="{ s:3, l:3 }" alignTo="middle">
                <div :slot="1" v-if="!$root.$data.global.loadingPatients" class="bb b--light-gray bg-white pa4 w-100">
                  <form>
                    <i class="fa fa-search absolute left-2"></i>
                    <input v-model="search" placeholder="Search by name, email or date of birth..." @keydown="updateInput($event)" type="text" class="b--none w-100 pl4" />
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
                    <td class="pt2 pb2 tr pr2">{{ patient.date_of_birth }}</td>
                  </tr>
                </table>

              </div>

              <Modal :active="activeModal" :onClose="modalClose">
                <div class="inline-centered">
                  <h1>Warning</h1>
                  <p>You are about to access personal health information for client <b>{{ name }}</b>. By accessing this document you hereby agree that you have been given permission to access this private health record. Please note, all actions will be recorded in this area.</p>
                  <button @click="modalClose" class="button">Go Back</button>
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
                    <Grid :flexAt="'l'" :columns="[{ l:'2of3' }, { l:'1of3' }]">
                        <div :slot="1">
                          <form>
                            <i class="fa fa-search absolute left-2"></i>
                            <input v-model="search" placeholder="Type anything to go back to the search..." @keydown="updateInput($event)" type="text" class="b--none w-100 pl4" />
                          </form>
                        </div>

                        <!-- Actions -->
                        <div :slot="2" class="absolute top-1 right-0 pr3 w-33">
                          <Grid :flexAt="'l'" :columns="[{ s:'1of2' }, { s:'1of2' }]" :gutters="{ s:2, l:2 }">
                            <span :slot="1" class="custom-select">
                              <select class="f3 h-100" @change="updateMenu($event)">
                                <option v-for="menuItem in dropDownMenu">{{ menuItem }}</option>
                              </select>
                            </span>
                            <button :slot="2" @click="newRecord" class="button dib fr w-40">New Record</button>
                          </Grid>
                        </div>
                    </Grid>
                  </div>

                  <div class="pa2 pa3-m">
                    <div v-if="page === 0">
                      <!-- <img class="inline-centered" src="images/if_ic_library_514023.svg" style="width: 70%;" alt=""> -->
                    </div>

                    <div v-if="page !== 0">
                      <!-- <div class="card-heading-container">
                        <h2 class="">
                          {{ page === 1 ? `${news ? 'New ' : ''}SOAP Note` : null }}
                          {{ page === 2 ? `${news ? 'New ' : ''}Lab Results` : null }}
                          {{ page === 3 ? `${news ? 'New ' : ''}Prescription` : null }}
                          {{ page === 4 ? `${news ? 'New ' : ''}Attachment` : null }}
                          {{ page === 5 ? `Intake Form` : null }}
                          {{ page === 6 ? `Treatment Plan` : null }}
                        </h2>
                        <h2 class="">
                          {{ selectedPatient.search_name }}
                        </h2>
                      </div> -->

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
                <Flyout class="hide-print" :active="true" :onClose="null" :button="true" :header="true" :heading="selectedPatient.search_name">
                  <a class="" :href="'mailto:' + selectedPatient.email">{{ selectedPatient.email }}</a>
                  <a class="" :href="'tel:' + selectedPatient.phone">{{ selectedPatient.phone }}</a>
                  <div class="" :style="`background-image: url(${selectedPatient.image});`" />
                  <div class="" />
                  <div class="input__container">
                    <div class="">
                      <span class="">ID: <b>#{{ selectedPatient.id }}</b></span>
                      <span class="">Joined: <b>{{ selectedPatient.created_at }}</b></span>
                      <span class="">DOB: <b>{{ selectedPatient.date_of_birth }}</b></span>
                    </div>
                    <div class="">
                      <span class="">City: <b>{{ selectedPatient.city }}</b></span>
                      <span class="">State: <b>{{ selectedPatient.state }}</b></span>
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

          <!-- Patient -->
          <div v-if="$root.$data.permissions === 'patient'">

            <!-- Loading state -->
            <Grid :flexAt="'l'" :columns="[{l: '1of1'}]" :gutters="{ s:3, l:3 }" alignTo="middle">
              <div :slot="1" v-if="patientLoading" class="pa2 pa3-m">
                <LoadingSpinner class="mt3" />
                <Paragraph class="tc mt2" :size="'large'">Your records are loading...</Paragraph>
              </div>
            </Grid>


            <div v-if="selectedUserPatient">
              {{ getTimelineData() }}
            </div>

            <div v-if="selectedUserPatient">
              <div class="">
                <div v-if="page === 0">
                  <img class="inline-centered" src="images/if_ic_library_514023.svg" style="width: 70%;" alt="">
                </div>
                <div class="card print-full-width" v-if="page !== 0">
                  <div class="card-heading-container">
                    <h2 class="">
                      {{ page === 1 ? `Treatment Plan` : null }}
                      {{ page === 2 ? `Lab Results` : null }}
                      {{ page === 3 ? `Prescription` : null }}
                      {{ page === 4 ? `Attachment` : null }}
                      {{ page === 5 ? `Intake Form` : null }}
                    </h2>
                    <h2 class="">
                      {{ selectedUserPatient.search_name }}
                    </h2>
                  </div>

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
              <div v-if="selectedUserPatient" class="hide-print">
                <Flyout :active="true" :onClose="null" :button="true" :header="true" :heading="selectedUserPatient.search_name">
                  <a class="" :href="'mailto:' + selectedUserPatient.email">{{ selectedUserPatient.email }}</a>
                  <a class="" :href="'tel:' + selectedUserPatient.phone">{{ selectedUserPatient.phone }}</a>
                  <div class="" :style="`background-image: url(${selectedUserPatient.image});`" />
                  <div class="" />
                  <div class="input__container">
                    <div class="">
                      <span class="">ID: <b>#{{ selectedUserPatient.id }}</b></span>
                      <span class="">Joined: <b>{{ selectedUserPatient.created_at }}</b></span>
                      <span class="">DOB: <b>{{ selectedUserPatient.date_of_birth }}</b></span>
                    </div>
                    <div class="">
                      <span class="">City: <b>{{ selectedUserPatient.city }}</b></span>
                      <span class="">State: <b>{{ selectedUserPatient.state }}</b></span>
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
              </div>

              <NotificationPopup
              :active="notificationActive"
              :comes-from="notificationDirection"
              :symbol="notificationSymbol"
              :text="notificationMessage"
              />

            </div>
          </div>


  </PageContainer>
</template>

<script>
import { LoadingSpinner } from 'feedback';
import { Paragraph, Heading2 } from 'typography';
import { Card, CardContent, Grid, PageHeader, PageContainer } from 'layout';

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
                                if (this.$root.$data.permissions === 'patient') {
                                    object.type = 'Treatment Plan';
                                }
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
        },
        setPatientLoading(bool) {
            this.patientLoading = bool;
        }
    },
    computed: {
        results() {
            if (this.$root.$data.permissions !== 'patient') {
                let array = this.$root.$data.global.patients;
                let matcher = new RegExp(this.search, 'ig');
                return array.filter(ele => {
                    return matcher.test(ele.search_name) ||
                                matcher.test(ele.email) ||
                                matcher.test(ele.date_of_birth);
                    });
            } else {
                return [];
            }
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
                    e.onClick = onClickFunctions[e.type].bind(this, e.data, i);
                    return e;
                });
                return arrays;
            },
            selectedUserPatient() {
                if (!this.$root.$data.global.user || !this.$root.$data.global.user.attributes || !this.$root.$data.global.user.included) {
                    return false;
                } else {
                    let patientData = this.$root.$data.global.user.included.attributes;
                    let patientUserData = this.$root.$data.global.user.attributes;
                    let patientUserId = this.$root.$data.global.user.id;
                    let patientId = this.$root.$data.global.user.included.id;
                    let object = {
                        address_1: patientUserData.address_1,
                        address_2: patientUserData.address_2,
                        city: patientUserData.city,
                        date_of_birth: moment(patientData.birthdate.date).format("MM/DD/YY"),
                        email: patientUserData.email,
                        has_a_card: patientUserData.has_a_card,
                        id: patientId,
                        name: `${patientUserData.last_name}, ${patientUserData.first_name}`,
                        phone: patientUserData.phone,
                        search_name: `${patientUserData.first_name} ${patientUserData.last_name}`,
                        state: patientUserData.state,
                        user_id: patientUserId,
                        zip: patientUserData.zip,
                        image: patientUserData.image_url,
                        created_at: moment(patientUserData.created_at.date).format("MM/DD/YY")
                    };
                    this.setPatientLoading(false);
                    return object;
                }
            }
        },
        watch: {
            results() {
                if (this.search !== '' && this.$root.$data.permissions !== 'patient') {
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
        },
        selectedUserPatient(val) {
            if (!val && this.$root.$data.global.user && this.$root.$data.global.user.id) {
                let patientData = this.$root.$data.global.user.included.attributes;
                let patientUserData = this.$root.$data.global.user.attributes;
                let patientUserId = this.$root.$data.global.user.id;
                let patientId = this.$root.$data.global.user.included.id;
                let object = {
                    address_1: patientUserData.address_1,
                    address_2: patientUserData.address_2,
                    city: patientUserData.city,
                    date_of_birth: moment(patientData.birthdate.date).format("MM/DD/YY"),
                    email: patientUserData.email,
                    has_a_card: patientUserData.has_a_card,
                    id: patientId,
                    name: `${patientUserData.last_name}, ${patientUserData.first_name}`,
                    phone: patientUserData.phone,
                    search_name: `${patientUserData.first_name} ${patientUserData.last_name}`,
                    state: patientUserData.state,
                    user_id: patientUserId,
                    zip: patientUserData.zip,
                    image: patientUserData.image_url,
                    created_at: moment(patientUserData.created_at.date).format("MM/DD/YY")
                };
                this.patientLoading = false;
                return object;
            } else {
                return false;
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

    .patient-row{
      cursor: pointer;

      &:hover {
        background-color: $color-gray-5;
      }
    }
    .content-with-flyout {
      padding-right: 350px;
    }
    .custom-select {
      margin-top: 0;
    }
</style>
