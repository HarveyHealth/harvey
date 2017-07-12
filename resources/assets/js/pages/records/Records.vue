<template>
  <div class="main-container">
    <UserNav />

      <div v-if="$root.$data.global.user.attributes.user_type !== 'client'">
        <div v-if="step == 1">
          <div class="main-content">
            <form class="form">
              <i class="fa fa-search search-icon"></i>
              <input v-modal="search" @keydown="updateInput($event)" type="text" class="search-bar" />
            </form>
            <Modal :active="activeModal" :onClose="modalClose">
              <div class="inline-centered">
                <h1>HIPAA Warning</h1>
                <p>You are about to access personal health information for client {{ selectPatient.search_name }}. By accessing this document you hereby agree that you have been given permission to access this private health record. Please note, all actions will be recorded in this area.</p>
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
          <div class="main-header">
            <div class="container container-backoffice">
              <h1 class="title header-xlarge">
                <span class="text">Records</span>
              </h1>
            </div>
          </div>

        </div>
      </div>

      <div v-if="$root.$data.global.user.attributes.user_type === 'client'">
        <div class="main-content">
          <div class="main-header">
            <div class="container container-backoffice">
              <h1 class="title header-xlarge">
                <span class="text">Records</span>
              </h1>
            </div>
          </div>

        </div>
      </div>

      

  </div>
</template>

<script>
import UserNav from '../../commons/UserNav.vue'
import Modal from '../../commons/Modal.vue'

export default {
    name: 'Records',
    components: {
        UserNav,
        Modal
    },
    data() {
        return {
          step: 1,
          results: [],
          search: '',
          selectedPatient: null,
          activeModal: false
        }
    },
    methods: {
      updateInput(e) {
        this.search = e.target.value
        let array = this.$root.$data.global.patients
        let matcher = new RegExp(this.value, 'ig')
        this.results = array.filter(ele => {
          return matcher.test(ele.search_name) ||
                      matcher.test(ele.email) ||
                      matcher.test(ele.date_of_birth)
        })
      },
      selectPatient(patient) {
        this.selectedPatient = patient
        this.activeModal = true
      },
      nextStep() {
        this.step = 2
      },
      modalClose() {
        this.activeModal = false
      }
    },
    computed: {
      results() {
        let array = this.$root.$data.global.patients
        let matcher = new RegExp(this.value, 'ig')
        this.results = array.filter(ele => {
          return matcher.test(ele.search_name) ||
                      matcher.test(ele.email) ||
                      matcher.test(ele.date_of_birth)
        })
      }
    },
    watch: {

    },
    mounted() {
        this.$root.$data.global.currentPage = 'records';
    }
}
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
