<template>
  <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>

      <div class="signup-form-container">
          <div class="text-centered">
            <img class="map" src="/images/signup/location.png" alt="">
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_text" name="zipcode" type="number" placeholder="Zip Code">
          </div>
          <div class="text-centered">
            <button @click="nextStep('practitioner')">Continue</button>
          </div>
      </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Where are you located?',
        subtitle: 'All consultations with doctors are virtual. However, we do not have licensed doctors in all 50 states. Please enter your zip code so we can veritfy that we are permitted to work with you. '
      }
    },
    name: 'Location',
    props: ['nextStep']
  }
</script>

<style>
  .map {
    margin-bottom: 40px;
    max-width: 142px;
  }
</style>
