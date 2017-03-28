<template>
  <main class="signup-content">
    <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>

      <div class="signup-form-container">
        <form>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="first_name" type="text" placeholder="First Name" required>
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="last_name" type="text" placeholder="Last Name" required>
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_text" name="phone_number" type="phone" placeholder="Phone Number" required>
          </div>

          <form @submit.prevent="nextStep('practioner')">
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
      title: 'We need a few more details…',
      subtitle: 'Please enter your full name and phone number where you would like the doctor to call you at the time of your phone consultation.'
    }
  },
  name: 'Phone',
  props: ['nextStep']
}
</script>

<style>
.example {
  color: red;
}
</style>
