<template>
  <div class="container small">

      <!-- progress indicator -->
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step"></li>
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
      </ul>

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>

      <div class="signup-form-container">
        <div class="input-wrap">
          <input class="form-input form-input_text" name="first_name" type="text" placeholder="First Name" v-model="firstname" v-validate="'required'">
          <span v-show="errors.has('first_name')" class="error-text">{{ errors.first('first_name') }}</span>
        </div>
        <div class="input-wrap">
          <input class="form-input form-input_text" name="last_name" type="text" placeholder="Last Name" v-model="lastname" v-validate="'required'">
          <span v-show="errors.has('last_name')" class="error-text">{{ errors.first('last_name') }}</span>
        </div>
        <div class="input-wrap text-centered">
          <input class="form-input form-input_text" name="phone_number" type="phone" placeholder="Phone Number" v-model="phone" v-validate="'required'">
          <span v-show="errors.has('phone_number')" class="error-text">{{ errors.first('phone_number') }}</span>
        </div>
        <div class="text-centered">
          <a class="button" @click.prevent="nextStep">Continue</a>
        </div>
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
        phone: '',
      }
    },
    methods: {
      nextStep() {
        this.$validator.validateAll().then(() => {
          this.$parent.next();
        }).catch(() => {

        });
      }
    },
    name: 'Phone'
  }
</script>

<style>

</style>
