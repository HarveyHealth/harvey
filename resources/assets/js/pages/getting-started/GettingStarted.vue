<template>
  <div>
    <router-view />
  </div>
</template>

<script>

  import Signup from './children/Signup.vue';
  import Welcome from './children/Welcome.vue';

  export default {
    name: 'getting-started',
    data() {
      return {
        new_registration: localStorage.getItem('new_registration') || false,
        signed_in: Laravel.user.signedIn
      }
    },
    components: {
      Signup,
      Welcome,
    },
    methods: {
      setBeforeUnload() {
        window.onbeforeunload = () => {
          return 'All your information will be reset.';
        }
      }
    },
    created() {
      // If new_registration exists, it means the user has just signed up and may go
      // through the signup funnel for one session only. If they refresh the page they
      // will be redirected to /dashboard
      if (this.new_registration) {
        this.new_registration = false;
        this.setBeforeUnload();
        localStorage.removeItem('new_registration');
        this.$router.push({ name: 'welcome', path: '/welcome' });
      } else if (this.signed_in) {
        this.setBeforeUnload();
        // window.location.href = '/dashboard';
      } else {
        this.$router.push({ name: 'sign-up', path: '/signup' });
      }
    }
  }
</script>
