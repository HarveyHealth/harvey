<template>
  <div>
    <header class="site-header">
      <svg class="harvey-logo"><use xlink:href="#harvey-logo" /></svg>
      <span class="header_phone-number">(800) 690-9989</span>
    </header>
    <main class="signup-content">
      <div class="container small"> <!-- This could be large, too -->
        <form>
          <transition name="fade" mode="out-in">
            <signup-step :is="currentStep" :next-step="nextStep"></signup-step>
          </transition>
        </form>
      </div>
    </main>
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
        currentStep: 'practioner'
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
