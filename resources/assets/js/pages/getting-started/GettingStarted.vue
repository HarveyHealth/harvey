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
    mounted() {
      if (Laravel.user.signedIn) this.setBeforeUnload();
    }
  }
</script>
