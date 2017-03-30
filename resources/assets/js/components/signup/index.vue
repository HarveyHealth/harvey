<template>
  <div>
    <header class="site-header">
      <svg class="harvey-logo"><use xlink:href="#harvey-logo" /></svg>
      <span class="header_phone-number">(800) 690-9989</span>
    </header>
    <main class="signup-content">
      <!-- <form> -->
        <transition name="fade" mode="out-in">
          <signup-step :is="currentStep" :next-step="nextStep"></signup-step>
        </transition>
      <!-- </form> -->
    </main>
  </div>
</template>

<script>
  import Registration from './_components/Registration.vue';
  import Phone from './_components/Phone.vue';
  import Practitioner from './_components/Practitioner.vue';
  import Location from './_components/Location.vue';
  import DateTime from './_components/DateTime.vue';
  import Modal from './_components/Modal.vue';

  export default {
    name: 'Signup',
    data() {
      return {
        currentStep: 'registration'
      }
    },
    components: {
      'registration': Registration,
      'phone': Phone,
      'practitioner': Practitioner,
      'location': Location,
      'datetime': DateTime,
      'modal': Modal
    },
    methods: {
      nextStep: function (step) {
        this.currentStep = step;
      }
    }
  }
</script>

<style>

</style>
