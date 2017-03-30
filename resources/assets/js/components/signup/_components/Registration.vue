<template>
  <div>

      <img class="registration-tree" src="/images/signup/tree.png" alt="">

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>

      <div class="signup-form-container">

          <div class="input-wrap">
            <input class="form-input form-input_text" name="first_name" type="text" placeholder="First Name">
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="last_name" type="text" placeholder="Last Name">
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_checkbox" type="checkbox" id="checkbox">
            <label class="form-label form-label_checkbox" for="checkbox">I agree to <a href="#">terms</a> and <a href="#">privacy policy</a>.</label>
          </div>
          <div class="text-centered">
            <input type="submit" class="btn btn-default" value="Continue">
          </div>

      </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'Your journey starts here',
        subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
        agree: ''
      }
    },
    name: 'Registration',
    props: ['nextStep']
  }
</script>

<style>

</style>
