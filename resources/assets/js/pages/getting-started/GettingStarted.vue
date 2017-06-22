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

    },
    created() {
      if (this.new_registration) {
        this.new_registration = false;
        localStorage.removeItem('new_registration');
        this.$router.push({ name: 'welcome', path: '/welcome' });
      } else if (this.signed_in) {
        window.location.href = '/dashboard';
      } else {
        this.$router.push({ name: 'sign-up', path: '/signup' });
      }
    }
  }
</script>
