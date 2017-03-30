<template>
  <div>
    <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
      </ul>

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>
    </div>
    <div class="container large">
      <div class="signup-form-container">
        <div class="flex-wrapper">
          <div class="input-wrap radio-block">
            one
          </div>

          <div class="input-wrap radio-block">
            two
          </div>
        </div>
        <button @click="nextStep('registration')">Submit</button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Choose date and time',
        subtitle: 'Tell us the best date and time you would like to schedule a phone consultation with a doctor. Remember, this is not in-person.'
      }
    },
    name: 'DateTime',
    props: ['nextStep']
  }
</script>

<style>

</style>
