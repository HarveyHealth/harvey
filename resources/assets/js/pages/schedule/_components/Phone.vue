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
          <input class="form-input form-input_text" name="first_name" type="text" placeholder="First Name" v-model="firstname" v-validate="'required'" />
          <span v-show="errors.has('first_name')" class="error-text">First name is required</span>
        </div>
        <div class="input-wrap">
          <input class="form-input form-input_text" name="last_name" type="text" placeholder="Last Name" v-model="lastname" v-validate="'required'" />
          <span v-show="errors.has('last_name')" class="error-text">Last name is required</span>
        </div>
        <div class="input-wrap">
          <input class="form-input form-input_text"
            name="phone_number"
            type="phone"
            placeholder="Phone Number"
            v-model="phone"
            v-validate="{ required: true, digits: 10 }"
            data-vv-validate-on="blur"
          />
          <span v-show="errors.has('phone_number')" class="error-text">Please supply a valid U.S. phone number.</span>
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
        subtitle: 'Please enter your full name and the phone number you would like your doctor to call at the time of your phone consultation.',
        firstname: '',
        lastname: '',
        phone: '',
      }
    },
    methods: {
      nextStep() {

        this.$validator.validateAll().then(() => {

          // update the User
          const userId = Laravel.user.id;

          axios.patch(`api/v1/users/${userId}`, {
            first_name: this.firstname,
            last_name: this.lastname,
            phone: this.phone,
          })
          .then(response => {
            this.$parent.next();
          })
          .catch(error => {
            console.log(error.response);
          });


        }).catch(() => {});
      }
    },
    name: 'Phone',
    mounted() {
      if (this.$parent.env === 'prod') {
        this.$ma.trackEvent({action: 'View Personal Contact Form', category: 'clicks', properties: {laravel_object: Laravel.user}})
      }
    }
  }
</script>

<style>

</style>
