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
        <form method="post" action="/datetime" @submit.prevent="onSubmit">
          <div class="text-centered">
            <img class="map" src="/images/signup/location.png" alt="">
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text error" name="zipcode" type="text" placeholder="Zip Code" v-model="zip">
            <span class="error-text">Please enter a valid zipcode</span>
          </div>
          <div class="text-centered">
            <input type="submit" class="button" value="Continue">
          </div>
        </form>
      </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Where are you located?',
        subtitle: 'All consultations with doctors are virtual. However, we do not have licensed doctors in all 50 states. Please enter your zip code so we can veritfy that we are permitted to work with you. ',
        zip: ''
      }
    },
    name: 'Location',
    methods: {
      onSubmit() {
        // For now, just go to the next page. We'll worry about validating the form later
        this.$router.push('/datetime');
      }
    }
  }
</script>

<style>
  .map {
    margin-bottom: 40px;
    max-width: 142px;
  }
</style>
