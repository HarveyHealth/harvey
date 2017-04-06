<template>
  <div class="container small">

      <img class="registration-tree" src="/images/signup/tree.png" alt="">

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>

      <div class="signup-form-container">
        <form method="post" action="/practitioner" @submit.prevent="onSubmit">
          <div class="input-wrap">
            <input class="form-input form-input_text" name="email" type="text" placeholder="Personal Email" v-model="email">
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="password" type="text" placeholder="Create Password" v-model="password">
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_checkbox" type="checkbox" id="checkbox" v-model="terms">
            <label class="form-label form-label_checkbox" for="checkbox">I agree to <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</label>
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
        title: 'Your journey starts here',
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
        email: '',
        password: '',
        terms: ''
      }
    },
    name: 'Registration',
    methods: {
      onSubmit() {
        // For now, just go to the next page. We'll worry about validating the form later
        this.$router.push('/practitioner');
      }
    }
  }
</script>

<style>

</style>
