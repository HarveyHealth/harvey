<template>
  <div>
    <header class="site-header">
      <svg class="harvey-logo"><use xlink:href="#harvey-logo" /></svg>
      <span class="header_phone-number">(800) 690-9989</span>
    </header>

    <transition name="fade" mode="out-in">
      <signup-step :is="currentStep" :next-step="nextStep"></signup-step>
    </transition>

  </div>
</template>

<script>
  import Registration from './_components/Registration.vue';
  import Phone from './_components/Phone.vue';
  import Practitioner from './_components/Practitioner.vue';
  import Location from './_components/Location.vue';

  export default {
    name: 'Signup',
    data() {
      return {
        steps: ['registration', 'phone', 'practioner'],
        completedSteps:[],
        currentStep: 'location'
      }
    },
    components: {
      'registration': Registration,
      'phone': Phone,
      'practioner': Practitioner,
      'location': Location
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
