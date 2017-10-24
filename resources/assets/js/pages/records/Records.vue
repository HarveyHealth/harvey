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
              <h1 style="color: #5f7278; text-align: center;">No records found.</h1>
            </div>
             <div v-if="!$root.$data.global.loadingPatients && search === ''" class="inline-centered">
              <i class="inline-centered fa fa-search" style="font-size: 300px; margin-top: 75px; height: 320px; width: 100%; color: #333;" />
              <h1 style="color: #666; text-align: center;">Search for records.</h1>
            </div>
            <Modal :active="activeModal" :onClose="modalClose">
              <div class="inline-centered">
                <h1>HIPAA Warning</h1>
                <p>You are about to access personal health information for client <b>{{ name }}</b>. By accessing this document you hereby agree that you have been given permission to access this private health record. Please note, all actions will be recorded in this area.</p>
                <button @click="modalClose" class="button" style="background-color: #ccc;">Go Back</button>
                <button @click="nextStep" class="button">Yes, I agree</button>
              </div>
            </Modal>
            <div class="container container-backoffice">
                <div v-if="search !== ''" v-for="patient in results" @click="selectPatient(patient)" class="results">
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
            <div style="top: 5px; position: absolute; right: 350px; z-index: 100; width: 330px;">
              <button @click="newSoapNote" class="button" style="background-color: #ccc; width: 150px; margin-right: 15px; float: left;">SOAP Note</button>
              <button class="button" style="width: 150px; margin-right: 15px; float: left;">New Record</button>
            </div>
              <div style="height: auto;">
                <div class="card" style="width: 70%;">
                  <div class="card-heading-container" style="height: 65px;">
                      <h2 style="font-weight: 400; float: left; color: #777777;">
                        {{ page === 1 ? 'New SOAP Note' : null }}
                        {{ page === 2 ? 'New Lab Results' : null }}
                        {{ page === 3 ? 'New Prescription' : null }}
                        {{ page === 4 ? 'New Attachment' : null }}
                        {{ page === 5 ? 'Intake Form' : null }}
                        {{ page === 6 ? 'Treatment Plan' : null }}
                      </h2>
                      <h2 style="font-weight: 400; float: right; color: #e3bab3;">
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
                <Flyout :active="true" :onClose="() => {}" :button="true" :header="true" :heading="selectedPatient.search_name" style=" width: 25%; z-index: 0;">
                  <a style="float: left; width: 51%; margin-bottom: 8px;" :href="'mailto:' + selectedPatient.email">{{ selectedPatient.email }}</a>
                  <a style="float: left; width: 51%;" :href="'tel:' + selectedPatient.phone">{{ selectedPatient.phone }}</a>
                  <div :style="`background-image: url(${selectedPatient.image});`" style="width: 100px; height: 100px; border-radius: 50%; position: absolute; right: 30px; border: 1px solid #ccc; background-size: cover;" />
                  <div style="border-bottom: 1px solid #d7dde3; margin-bottom: 30px; margin-top: 115px;" />
                  <div class="input__container" style="border-bottom: 1px solid #d7dde3; margin-bottom: 30px; padding-bottom: 20px;">
                    <div style="float: left; width: 50%;">
                      <span style="width: 100%; float: left;">ID: <b>#{{ selectedPatient.id }}</b></span>
                      <span style="width: 100%; float: left;">Joined: <b>{{ selectedPatient.created_at }}</b></span>
                      <span style="width: 100%; float: left;">DOB: <b>{{ selectedPatient.date_of_birth }}</b></span>
                    </div>
                    <div style="float: left; width: 50%;">
                      <span style="width: 100%; float: left;">City: <b>{{ selectedPatient.city }}</b></span>
                      <span style="width: 100%; float: left;">State: <b>{{ selectedPatient.state }}</b></span>
                    </div>
                  </div>
                  <div class="input__container">
                    <Timeline :index="index" :items="[
                      {
                        type: 'Intake Form',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                        onClick: () => {
                          this.setIndex(0);
                          this.intake();
                        }
                      },
                      {
                        type: 'Lab Results',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                        onClick: () => {
                          this.labResults();
                          this.setIndex(1);
                        }
                      },
                      {
                        type: 'Treatment Plan',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                        onClick: () => {
                          this.setIndex(2);
                          this.treatment();
                        }
                      },
                      {
                        type: 'Prescription',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                        onClick: () => {
                          this.prescription();
                          this.setIndex(3);
                        }
                      },
                      {
                        type: 'Attachment',
                        date: 'Wednesday, July 26th 2017',
                        doctor: 'Dr. Amanda Frick, ND',
                        onClick: () => {
                          this.attachment();
                          this.setIndex(4);
                        }
                      }
                    ]" />
                  </div>
                </Flyout>

              </div>
          </div>
        </div>

      <div v-if="$root.$data.permissions === 'patient'">
        <div class="main-content">
          <div class="main-header">
            <div class="container container-backoffice">
              <h1 class="header-xlarge">
                <span class="text">Records</span>
              </h1>
            </div>
          </div>

          <div class="card" style="width: 76%;">
              <div class="card-heading-container">
                  <div>
                    {{ $root.$data.global.user.attributes.doctor_name }} with {{ $root.$data.global.user.attributes.first_name }} {{ $root.$data.global.user.attributes.last_name }}
                  </div>
              </div>
              <div style="height: 600px; padding: 10px; overflow-x: hidden; overflow-y: scroll;">
                  <div style="float: left; height: 400px; width: 60%; position: relative; top: 15px;">
                    <h7 name="" class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Treatment</h7>
                    <p style="margin: 15px; padding: 5px;">
                        Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                        <br>
                        <br>
                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                        <br>
                        <br>
                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                    </p>
                  </div>
                  <div style="float: left; height: 400px; width: 35%; position: relative; top: 15px;">
                    <div style="padding: 10px;">
                      <h7 name="" class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Client Intake</h7>
                      <div class="inline-centered" style="background-color: #f8f8f8; height: 100px;">
                        <button class="button" style="margin: 33px auto;">Intake Form</button>
                      </div>
                    </div>
                    <div style="padding: 10px;">
                      <h7 name="" class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Prescriptions</h7>
                      <div class="inline-centered" style="background-color: #f8f8f8; height: 100px;">
                        <button class="button" style="margin: 33px auto;">Prescriptions</button>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
          <Flyout :active="true" :onClose="() => {}" :heading="selectedPatient" style="width: 20%; z-index: 0;">

            <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;" />
          </Flyout>

        </div>
      </div>

  </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue'
import Modal from '../../commons/Modal.vue'
import Flyout from '../../commons/Flyout.vue'
import Timeline from '../../commons/Timeline.vue'
import SoapNote from './components/SoapNote.vue'
import LabResults from './components/LabResults.vue'
import Prescription from './components/Prescription.vue'
import Attachment from './components/Attachment.vue'
import Intake from './components/Intake.vue'
import Treatment from './components/Treatment.vue'
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
          page: 1,
          index: null
        }
    },
    methods: {
      updateInput(e) {
        this.step = 1;
        this.activeModal = false;
        this.search = e.target.value;
      },
      newSoapNote() {
        this.page = 1;
        this.index = null;
      },
      soapNote() {
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
        this.step = 2
        this.search = '';
      },
      modalClose() {
        this.activeModal = false;
      }
    },
    computed: {
      results() {
        let array = this.$root.$data.global.patients
        let matcher = new RegExp(this.search, 'ig')
        return array.filter(ele => {
          return matcher.test(ele.search_name) ||
                      matcher.test(ele.email) ||
                      matcher.test(ele.date_of_birth)
        })
        return this.showing;
      }
    },
    watch: {
      results() {
        if (this.search !== '') {
          this.step = 1;
          this.activeModal = false;
          this.selectedPatient = null;
          let array = this.$root.$data.global.patients
          let matcher = new RegExp(this.search, 'ig')
          return array.filter(ele => {
            return matcher.test(ele.search_name) ||
                        matcher.test(ele.email) ||
                        matcher.test(ele.date_of_birth)
          })
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
