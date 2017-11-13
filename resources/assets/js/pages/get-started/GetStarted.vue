<template>
  <div class="font-centered height-100">
    <div class="bg-blue-fade"></div>
    <div v-if="!isSignup" class="phone-container">
      <a href="tel:800-690-9989" class="button is-outlined is-hidden-mobile">(800) 690-9989</a>
    </div>
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
    computed: {
      isSignup() {
        return App.Router.history.current.name === 'sign-up';
      }
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

<style lang="scss" scoped>
  .phone-container {
    height: 58px;
    margin: 0 auto;
    max-width: 1152px; // same as Discovery navigation
  }

  .button {
    border-width: 1px;
    color: white;
    float: right;
    font-weight: 400;
    margin: 24px 24px 0;
  }

  .height-100 {
    height: calc(100% - 58px);
  }
</style>
