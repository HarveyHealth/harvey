<template>
  <div class="main-container">
    <UserNav />
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

        }
    },
    methods: {

    },
    computed: {

    },
    mounted() {
        
    }
}
</script>
