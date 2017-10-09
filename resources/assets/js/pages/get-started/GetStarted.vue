<template>
  <div class="font-centered">
    <div class="bg-blue-fade"></div>
    <MainNav :context="'questions'" />
    <router-view />
  </div>
</template>

<script>
  import Shared from '../../v2/components/shared';
  import Signup from './children/Signup.vue';
  import Welcome from './children/Welcome.vue';

  export default {
    name: 'get-started',
    components: {
      MainNav: Shared.MainNav,
      Signup,
      Welcome,
    },
    beforeCreate() {
      const zipValidation = localStorage.getItem('harvey_zip_validation');
      if (!zipValidation) {
        window.location.href = '/conditions';
      } else {
        App.setState('getstarted.userPost.zip', JSON.parse(zipValidation).zip);
      }
    },
    mounted() {
      if (App.Config.user.isLoggedIn) {
        window.onbeforeunload = () => {
          return 'All your information will be reset.';
        }
      }
    }
  }
</script>
