<template>
  <div>
    <form method="post" action="/" @submit.prevent="onSubmit" v-show="!isComplete">

      <div class="container small">
        <img class="registration-tree" src="/images/signup/tree.png" alt="">

        <h1 class="header-xlarge">{{ title }}</h1>
        <p class="large">{{ subtitle }}</p>

        <div class="signup-form-container">
          <div class="input-wrap">
            <input class="form-input form-input_text" name="email" type="email" placeholder="Personal Email" v-model="email">
            <!-- <span class="error-text" v-text="errors"></span> -->
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="password" type="password" placeholder="Create Password" v-model="password">
            <!-- <span class="error-text" v-text="errors"></span> -->
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text error" name="zipcode" type="text" placeholder="Zip Code" v-model="zip">
            <!-- <span class="error-text" v-text="errors"></span> -->
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_checkbox" type="checkbox" id="checkbox" v-model="terms">
            <label class="form-label form-label_checkbox" for="checkbox">I agree to <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</label>
            <!-- <span class="error-text" v-text="errors"></span> -->
          </div>
        </div>

      </div>

      <div class="text-centered">
        <input type="submit" class="button" value="Sign Up">
      </div>
    </form>

    <!-- Intertitial -->
    <interstitial :zipInRange="true" v-if="isComplete" />

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
        terms: false,
        errors: {},
        isComplete: false,
      }
    },
    components: {
      interstitial: Interstitial,
    },
    methods: {
      onSubmit() {
        console.log("form submitted");

        this.isComplete = true;

        /*
        axios.post('api/v1/users', {
          // email: this.email,
          // password: this.password,
          // zip: this.zip,
          // terms: this.terms,
        })
        .then(response => {
          // TODO: is zip code out of range? tag it
          // continue to next step
          this.$router.push('/confirmation');
        })
        .catch(error => this.errors = error.response.data.errors);
        */
      },
    },
  }
</script>

<style>

</style>
