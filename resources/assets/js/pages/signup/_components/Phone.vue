<template>
  <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>

      <div class="signup-form-container">
        <form method="post" action="/practitioner" @submit.prevent="onSubmit">
          <div class="input-wrap">
            <input class="form-input form-input_text" name="first_name" type="text" placeholder="First Name" v-model="firstname">
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="last_name" type="text" placeholder="Last Name" v-model="lastname">
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_text" name="phone_number" type="phone" placeholder="Phone Number" v-model="phone">
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
        title: 'We need a few more details…',
        subtitle: 'Please enter your full name and phone number where you would like the doctor to call you at the time of your phone consultation.',
        firstname: '',
        lastname: '',
        phone: ''
      }
    },
    name: 'Phone',
    methods: {
      onSubmit() {
        // For now, just go to the next page. We'll worry about validating the form later
        this.$router.push('/location');
      }
    }
  }
</script>

<style>

</style>
