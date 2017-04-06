<template>
  <div class="container small">

      <img class="registration-tree" src="/images/signup/tree.png" alt="">

      <h1 class="header-xlarge">{{ title }}</h1>
      <p class="large">{{ subtitle }}</p>

      <div class="signup-form-container">
        <div class="input-wrap">
          <input class="form-input form-input_text" name="email" type="email" placeholder="Personal Email" v-model="email" @blur="validate">
        </div>
        <div class="input-wrap">
          <input class="form-input form-input_text" name="password" type="password" placeholder="Create Password" v-model="password" @blur="validate">
        </div>
        <div class="input-wrap text-centered">
          <input class="form-input form-input_checkbox" type="checkbox" id="checkbox" v-model="terms" @change="validate">
          <label class="form-label form-label_checkbox" for="checkbox">I agree to <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</label>
        </div>
        <div class="text-centered">
          <button class="button" :disabled="!validated" @click.prevent="createAccount">Continue</button>
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
        email: '',
        password: '',
        terms: false,
        validated: false,
      }
    },
    created() {
      console.log(this.$parent.step);
    },
    methods: {
      validate() {

        if (this.email != '' && this.password != '' && this.terms) { //TODO: get actual validation going
          this.validated = true;
        } else {
          console.log('not valid yet');
        }

        // this.validated = this.terms;
        //if (this.validated) this.createAccount();
      },
      createAccount() {
        // bundle account data to send along to parent
        const accountData = {
          email: this.email,
          password: this.password,
          terms: this.terms,
        };

        this.$parent.submitAccountInformation(accountData);
      }
    },
    name: 'Registration'
  }
</script>

<style>

</style>
