<template>
  <main class="signup-content">
    <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">Choose your practioner</h1>
      <p class="large">Tell us which type of integrative doctor you would like to partner with. If this is your first time seeking advice for a specific ailment, we recommend a Naturopathic Doctor.</p>
    </div>

    <div class="container">
      <div class="signup-form-container large">
        <form>


          <form @submit.prevent="nextStep('registration')">
            <input type="submit" class="btn btn-default" value="Continue">
          </form>
        </form>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  data() {
    return {
      title: 'Choose your practioner',
      subtitle: 'Tell us which type of integrative doctor you would like to partner with. If this is your first time seeking advice for a specific ailment, we recommend a Naturopathic Doctor.'
    }
  },
  name: 'Practitioner',
  props: ['nextStep']
}
</script>

<style>
.example {
  color: red;
}
</style>
