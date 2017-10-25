<template>
  <div class="main-container">
    <UserNav />

      <div v-if="$root.$data.permissions !== 'patient'">
        <div v-if="step == 1">
          <div class="main-content">
            <form class="form">
              <i class="fa fa-search search-icon"></i>
              <input v-modal="search" placeholder="Search by name, email or date of birth..." type="text" class="search-bar" />
            </form>
            <Modal :active="activeModal" :onClose="modalClose">
              <div class="inline-centered">
                <h1>HIPAA Warning</h1>
                <p>You are about to access personal health information for client <b>{{ name }}</b>. By accessing this document you hereby agree that you have been given permission to access this private health record. Please note, all actions will be recorded in this area.</p>
                <button @click="nextStep" class="button">Yes, I agree</button>
              </div>
            </Modal>
            <div class="container container-backoffice">
                <div v-for="patient in results" @click="selectPatient(patient)" class="results">
                    <div class="spacing">{{ patient.search_name }}</div>
                    <div class="spacing">{{ patient.email }}</div>
                    <div class="spacing">{{ patient.date_of_birth }}</div>
                </div>
            </div>
          </div>
        </div>
        <div v-if="step == 2" class="main-content">
           <div>
            <form class="form">
              <i class="fa fa-search search-icon"></i>
              <input v-modal="search" placeholder="Search by name, email or date of birth..." @keydown="updateInput($event)" type="text" class="search-bar" />
            </form>

              <div style="height: 800px;">  
                <div class="card" style="width: 76%;">
                  <div class="card-heading-container">
                      <div>
                        Doctor with Patient
                      </div>
                  </div>
                    <div style="height: 800px; padding: 10px; overflow-x: hidden; overflow-y: scroll;">  
                        
                        <div style="float: left; width: 64%; position: relative; top: 25px;">
                          <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Subject</h7>
                          <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
                        </div>

                        <div style="float: left; width: 35%; position: relative; top: 15px; left: 15px;">
                          <div style="padding: 10px;">
                            <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Client Intake</h7>
                            <div class="inline-centered" style="background-color: #f8f8f8; height: 100px; margin: 15px;">
                              <button class="button" style="margin: 33px auto;">Intake Form</button>
                            </div>
                          </div>
                        </div>

                        <div style="width: 97%; position: relative; top: 15px;">
                            <h7 class="card-header" style="height: 20px; margin: 20px 15px; padding: 5px;">Objective</h7>
                            <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
                          </div>

                          <div style="width: 97%; position: relative; top: 15px;">
                            <h7 class="card-header" style="height: 20px; margin: 20px 15px; padding: 5px;">Assessment</h7>
                            <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;"/>
                          </div>

                          <div style="color: #EDA1A6; padding: 5px; padding-top: 10px; width: 97%; margin: 0 20px;">
                               - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                          </div>

                            <div style="float: left; width: 64%; position: relative; top: 25px;">
                              <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Treatment</h7>
                              <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
                            </div>

                            <div style="float: left; width: 35%; position: relative; top: 15px; left: 15px;">
                              <div style="padding: 10px;">
                                <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Prescription</h7>
                                <div class="inline-centered" style="background-color: #f8f8f8; height: 100px; margin: 15px;">
                                  <button class="button" style="margin: 33px auto;">Prescription</button>
                                </div>
                              </div>
                          </div>
                          
                          <div class="inline-centered">
                            <button class="button" style="margin-top: 35px;">Save Changes</button>
                          </div>

                      </div>

                </div>
                <Flyout :active="true" :onClose="null" heading="Record History" style="width: 20%; z-index: 0;">
                  <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
                    <div class="input__container">
                        <label class="input__label" for="patient_name">name</label>
                        <div style="color: #82BEF2; position: relative; bottom: 30px; float: right; width: 125px; height: 125px;" class="input__label" for="patient_name">image</div>
                        <div style="color: #82BEF2; width: 100%;" class="input__label" for="patient_name">email</div>
                        <div style="color: #82BEF2;" class="input__label" for="patient_name">phone</div>
                    </div>
                  </div>
                   <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
                    <div class="input__container">
                        <span style="color: #82BEF2; width: 50%;" class="input__label" for="patient_name">ID</span>
                         <span style="color: #82BEF2; width: 50%;" class="input__label" for="patient_name">JOINED</span>
                        <span style="color: #82BEF2; width: 50%;" class="input__label" for="patient_name">DOB</span>
                        <span style="color: #82BEF2; width: 50%;" class="input__label" for="patient_name">CITY</span>
                        <span style="color: #82BEF2; width: 100%;" class="input__label" for="patient_name">STATE</span> 
                    </div>
                   </div>  
                  <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
                    <div class="input__container">
                        <label class="input__label" for="patient_name">appointments</label>
                        <div style="padding: 15px 0;">
                          <span style="color: #82BEF2; float: left;" class="input__label" for="patient_name">Dr. Amanda Frick</span>
                          <span style="color: #82BEF2; float: right;" class="input__label" for="patient_name">12/18/15</span>
                        </div>
                    </div>
                  </div>
                  <div class="input__container">
                      <label class="input__label" for="patient_name">lab tests</label>
                      <div style="padding: 15px 0;">
                        <span style="color: #82BEF2; float: left;" class="input__label" for="patient_name">Micronutrients</span>
                        <span style="color: #82BEF2; float: right;" class="input__label" for="patient_name">12/18/15</span>
                      </div>
                  </div>
                </Flyout>

              </div>
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
                    <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Treatment</h7>
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
                      <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Client Intake</h7>
                      <div class="inline-centered" style="background-color: #f8f8f8; height: 100px;">
                        <button class="button" style="margin: 33px auto;">Intake Form</button>
                      </div>
                    </div>
                    <div style="padding: 10px;">
                      <h7 class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Prescriptions</h7>
                      <div class="inline-centered" style="background-color: #f8f8f8; height: 100px;">
                        <button class="button" style="margin: 33px auto;">Prescriptions</button>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
          <Flyout :active="true" :onClose="null" heading="Record History" style="width: 20%; z-index: 0;">
            <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
              <div class="input__container">
                  <label class="input__label" for="patient_name">lab notes</label>
                  <span style="color: #82BEF2; float: left;" class="input__label" for="patient_name">Dr. Amanda Frick</span>
                  <span style="color: #82BEF2; float: right;" class="input__label" for="patient_name">12/18/15</span>
              </div>
            </div>
            <div style="border-bottom: 1px solid #F4F4F4; margin-bottom: 30px;">
              <div class="input__container">
                  <label class="input__label" for="patient_name">appointments</label>
                  <span style="color: #82BEF2; float: left;" class="input__label" for="patient_name">Dr. Amanda Frick</span>
                  <span style="color: #82BEF2; float: right;" class="input__label" for="patient_name">12/18/15</span>
              </div>
            </div>
            <div class="input__container">
                <label class="input__label" for="patient_name">lab tests</label>
                <span style="color: #82BEF2; float: left;" class="input__label" for="patient_name">Micronutrients</span>
                <span style="color: #82BEF2; float: right;" class="input__label" for="patient_name">12/18/15</span>
            </div>
          </Flyout>

        </div>
      </div>

      

  </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue';
import Modal from '../../commons/Modal.vue';
import Flyout from '../../commons/Flyout.vue';
export default {
    name: 'Records',
    components: {
        UserNav,
        Modal,
        Flyout
    },
    data() {
        return {
          step: 1,
          search: '',
          selectedPatient: null,
          activeModal: false,
          name: ''
        };
    },
    methods: {
      selectPatient(patient) {
        this.selectedPatient = patient;
        this.name = patient.search_name;
        this.activeModal = true;
      },
      nextStep() {
        this.step = 2;
      },
      modalClose() {
        this.activeModal = false;
      }
    },
    computed: {
      results() {
        let array = this.$root.$data.global.patients;
        if (!array.length) return [];
        let matcher = new RegExp(this.value, 'ig');
        return array.filter(ele => {
          return matcher.test(ele.search_name) ||
                      matcher.test(ele.email) ||
                      matcher.test(ele.date_of_birth);
        });
      }
    },
    watch: {
      results(val, old) {
        if (val !== old) {
          let array = this.$root.$data.global.patients;
          if (!array.length) return [];
          let matcher = new RegExp(this.value, 'ig');
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