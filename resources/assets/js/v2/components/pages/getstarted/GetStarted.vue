<template>
  <div>
    <router-view />
  </div>
</template>

<script>
  import Signup from './children/Signup.vue';
  import Welcome from './children/Welcome.vue';

  export default {
    name: 'get-started',
    components: {
      Signup,
      Welcome
    },
    beforeCreate() {
      // The /get-started funnel is technically now only for users with a
      // serviceable zip code, so we're going to reroute the user to
      // /conditions if they do not have zip_validation data stored locally
      // (which happens at the end of the /conditions funnel if the user's
      // zip code is valid). If they do have zip_validation data stored,
      // we assign that to conditions state and render the component.
      const zipValidation = App.Logic.getstarted.getZipValidation();
      if (zipValidation) {
        App.setState('getstarted.zipValidation', zipValidation);
        App.setState('getstarted.userPost.zip', zipValidation.zip);
      }
    },
    mounted() {
      // If the user is logged in we want to make it so that they are warned
      // before navigating away from the page that they're selections will be
      // overwritten.
      if (App.Config.user.isLoggedIn) {
        window.onbeforeunload = () => {
          return 'All your information will be reset.';
        };
      }
    }
  };
</script>
