<template>
  <div class="font-centered height-100">
    <div class="bg-blue-fade"></div>
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
      Welcome
    },
    beforeMount() {
      const zipValidation = App.Logic.getstarted.getZipValidation();
      if (!zipValidation) {
        window.location.href = '/conditions';
      } else {
        App.setState('getstarted.zipValidation', zipValidation);
        App.setState('getstarted.userPost.zip', zipValidation.zip);
      }
    },
    mounted() {
      if (App.Config.user.isLoggedIn) {
        window.onbeforeunload = () => {
          return 'All your information will be reset.';
        };
      }
    }
  };
</script>
