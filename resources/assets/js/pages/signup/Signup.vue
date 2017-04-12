<template>
  <div>
    <form @submit.prevent="onSubmit" v-show="!isComplete">

      <div class="container small">
        <img class="registration-tree" src="/images/signup/tree.png" alt="">

        <h1 class="header-xlarge">{{ title }}</h1>
        <p class="large">{{ subtitle }}</p>

        <div class="signup-form-container">
          <div class="input-wrap">
            <input class="form-input form-input_text" name="email" type="email" placeholder="Personal Email" v-model="email" v-validate="'required|email'" />
            <span v-show="errors.has('email')" class="error-text">{{ errors.first('email') }}</span>
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="password" type="password" placeholder="Create Password" v-model="password" v-validate="'required'" />
            <span v-show="errors.has('password')" class="error-text">{{ errors.first('password') }}</span>
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text error" name="zipcode" type="text" placeholder="Zip Code" v-model="zip" v-validate="'required'" />
            <span v-show="errors.has('zipcode')" class="error-text">{{ errors.first('zipcode') }}</span>
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_checkbox" name="terms" type="checkbox" id="checkbox" v-model="terms" v-validate="'required'" />
            <label class="form-label form-label_checkbox" for="checkbox">I agree to <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</label>
            <span v-show="errors.has('terms')" class="error-text">{{ errors.first('terms') }}</span>
          </div>
        </div>

      </div>

      <div class="text-centered">
        <input type="submit" class="button" value="Sign Up">
      </div>
    </form>

    <!-- Intertitial -->
    <interstitial :zipInRange="zipInRange" v-if="isComplete" />

  </div>
</template>

<script>

  import Interstitial from './Interstitial.vue';

  export default {
    name: 'Signup',
    data() {
      return {
        title: 'Your journey starts here',
        subtitle: 'Create an account', //TODO: get some real copy here
        email: '',
        password: '',
        zip: '',
        zipInRange: false,
        terms: false,
        isComplete: false,
      }
    },
    components: {
      interstitial: Interstitial,
    },
    methods: {
      onSubmit() {

        // Validate the form
        this.$validator.validateAll().then(() => {
            this.isComplete = true;

            // create the account
            axios.post('api/v1/users', {
              email: this.email,
              password: this.password,
              zip: this.zip,
              terms: this.terms,
            })
            .then(response => {
              // TODO: is zip code out of range? tag it
              // continue to next step
              // this.$router.push('/confirmation');
              console.log(response);
            })
            .catch(error => this.errors = error.response.data.errors);

        }).catch(() => {
            //alert('Correct them errors!');
        });
      },
    },
  }
</script>

<style>

</style>
