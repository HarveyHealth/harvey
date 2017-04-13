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
          <masked-input
            class="form-input form-input_text"
            name="phone_number"
            type="phone"
            placeholder="Phone Number"
            mask="\+\1 (111) 111-1111"
            v-model="phone"
            required
            @input="phoneRawValue = arguments[1]"
          />
          <span v-show="phoneError" class="error-text">Please supply a valid U.S. phone number</span>
        </div>
        <div class="text-centered">
          <a class="button" @click.prevent="nextStep">Continue</a>
        </div>
      </div>
  </div>
</template>

<script>
  import MaskedInput from 'vue-masked-input';

  export default {
    data() {
      return {
        title: 'We need a few more details…',
        subtitle: 'Please enter your full name and the phone number you would like your doctor to call at the time of your phone consultation.',
        firstname: '',
        lastname: '',
        phone: '',
        phoneError: false,
        phoneRawValue: '',
      }
    },
    components: {
      MaskedInput,
    },
    methods: {
      nextStep() {

        // we need to custom check for a phone number
        // is it empty? is it long enough? does it have pre-mask values?
        if (this.phoneRawValue === '' ||
            this.phoneRawValue.length !== 10 ||
            this.phoneRawValue.indexOf('_') !== -1) {
          this.phoneError = true;
        } else {
          this.phoneError = false;
        }

        console.log(this.phoneRawValue.length, 'length');
        console.log(this.phoneRawValue.indexOf('_'));

        this.$validator.validateAll().then(() => {
          if (!this.phoneError) {
            //this.$parent.next();
          }

          //console.log(Laravel.user);

        }).catch(() => {});
      }
    },
    name: 'Phone'
  }
</script>

<style>

</style>
