<template>
  <div>
    <router-view />
  </div>
</template>

<script>
  import Signup from './children/Signup';
  import Welcome from './children/Welcome';

  export default {
    name: 'get-started',
    components: {
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
