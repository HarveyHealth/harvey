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
                <h1>HIPAA Warning</h1>
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
              <input v-model="search" placeholder="Search by name, email or date of birth..." @keydown="updateInput($event)" type="text" class="search-bar" />
            </div>
            <div class="records-button-container">
              <button @click="newSoatNote" class="button soat-button">SOAT Note</button>
              <button class="button records-button">New Record</button>
            </div>
              <div class="auto-height">
                <div v-if="page === 0">
                  <img class="inline-centered height500" src="images/if_ic_library_514023.svg" style="width: 70%;" alt="">
                </div>
                <div class="card width70" v-if="page !== 0">
                  <div class="card-heading-container height65">
                      <h2 class="left-records-label">
                        {{ page === 1 ? 'New SOAT Note' : null }}
                        {{ page === 2 ? 'New Lab Results' : null }}
                        {{ page === 3 ? 'New Prescription' : null }}
                        {{ page === 4 ? 'New Attachment' : null }}
                        {{ page === 5 ? 'Intake Form' : null }}
                        {{ page === 6 ? 'Treatment Plan' : null }}
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
                    <div v-if="page === 5">
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
                    <Timeline :index="index" :items="timelineData" />
                  </div>
                </Flyout>

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
        Treatment
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
          index: null
        };
    },
    methods: {
        updateInput(e) {
            this.step = 1;
            this.page = 0;
            this.setIndex(null);
            this.activeModal = false;
            this.search = e.target.value;
        },
        newSoatNote() {
            this.page = 1;
            this.index = null;
        },
        soaTNote() {
            this.page = 1;
        },
        labResults() {
            this.page = 2;
        },
        prescription() {
            this.page = 3;
        },
        attachment() {
            this.page = 4;
        },
        intake() {
            this.page = 5;
        },
        treatment() {
            this.page = 6;
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
        },
        modalClose() {
            this.activeModal = false;
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
                    'Intake Form': () => {
                        this.setIndex(0);
                        this.intake();
                    },
                    'Lab Results': () => {
                        this.setIndex(1);
                        this.labResults();
                    },
                    'Treatment Plan': () => {
                        this.setIndex(2);
                        this.treatment();
                    },
                    'Prescription': () => {
                        this.setIndex(3);
                        this.prescription();
                    },
                    'Attachment': () => {
                        this.setIndex(4);
                        this.attachment();
                    },
                };
                let arrays = [
                    {
                        type: 'Intake Form',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                      },
                      {
                        type: 'Lab Results',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                      },
                      {
                        type: 'Treatment Plan',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                      },
                      {
                        type: 'Prescription',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                      },
                      {
                        type: 'Attachment',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                      }
                ];
                arrays.map(e => {
                    e.onClick = onClickFunctions[e.type];
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

<style lang="scss">
    .records-loading {
        height: 70px;
        padding: 20px;
        margin: 0;
        font-family: 'proxima-nova';
        font-weight: 300;
    }
    .full-width {
        width: 100%;
    }
    .height500 {
        height: 500px;
    }
    .no-records-label {
        color: #5f7278; 
        text-align: center;
    }
    .soat-button {
        background-color: #ccc; 
        width: 150px; 
        margin-right: 15px; 
        float: left;
    }
    .full-left {
        width: 100%; 
        float: left;
    }
    .half-left {
        width: 50%; 
        float: left;
    }
    .records-button {
        width: 150px; 
        margin-right: 15px; 
        float: left;
    }
    .grey-background {
        background-color: #ccc;
    }

    .auto-height {
        height: auto;
    }
    .records-button-container {
        top: 5px;
        position: absolute;
        right: 350px;
        z-index: 100;
        width: 330px;
    }
    .search-div-icon {
        font-size: 300px; 
        margin-top: 75px; 
        height: 320px; 
        width: 100%; 
        color: #333;
    }
    .search-records-label {
        color: #666; 
        text-align: center;
    }
    .form {
        background-color: white;
    }
    .search-icon {
        position: absolute;
        font-size: 20px;
        top: 15px;
        left: 10px;
        color: #ccc;
    }
    .search-bar {
        margin-left: 50px;
        width: 100%;
        border: none;
        background: transparent;
        height: 50px;
        color: #777777;
    }
    .height65 {
        height: 65px;
    }
    .records-divider {
        border-bottom: 1px solid #d7dde3; 
        margin-bottom: 30px; 
        margin-top: 125px;
    }
    .disable-flyout {
        width: 25%; 
        z-index: 0;
    }
    .mid-section-flyout {
        border-bottom: 1px solid #d7dde3; 
        margin-bottom: 30px; 
        padding-bottom: 20px;
    }
    .records-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        position: absolute;
        right: 30px;
        border: 1px solid #ccc;
        background-size: cover;
    }
    .flyout-links {
        float: left; 
        width: 51%; 
        margin-bottom: 8px;
    }
    .width70 {
        width: 70%;
    }
    .search-name-label {
        font-weight: 400; 
        float: right; 
        color: #e3bab3;
    }
    .left-records-label {
        font-weight: 400; 
        float: left; 
        color: #777777;
    }
    .results {
        width: 100%;
        margin: 5px;
        float: left;
        &:hover {
        background-color: rgba(84, 166, 237, 0.6);
        color: white;
        }
    }
    .spacing {
        font-size: 22px;
        float: left;
        width: 33.3%;
        padding: 5px;
    }
</style>
