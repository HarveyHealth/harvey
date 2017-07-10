<template>
  <div class="main-container">
    <UserNav />

      <div v-if="$root.$data.global.user.attributes.user_type !== 'client'">
        <div v-if="step == 1">
          <div class="main-content">
            <form class="form">
              <i class="fa fa-search search-icon"></i>
              <input v-modal="value" type="text" class="search-bar" />
            </form>
            <div class="container container-backoffice">
                <div v-for="patient in results" style="width: 100%; padding: 20px;">
                    <div style="font-size: 22px; float: left; width: 33.3%;">{{ patient.search_name }}</div>
                    <div style="font-size: 22px; float: left; width: 33.3%;">{{ patient.email }}</div>
                    <div style="font-size: 22px; float: left; width: 33.3%;">{{ patient.date_of_birth }}</div>
                </div>
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

export default {
    name: 'Records',
    components: {
        UserNav
    },
    data() {
        return {
          step: 1,
          results: [],
          value: ''
        }
    },
    methods: {

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

<style>
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
</style>
