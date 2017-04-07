<template>
  <div>
    <form method="post" action="/" @submit.prevent="onSubmit">

      <div class="container small">
        <img class="registration-tree" src="/images/signup/tree.png" alt="">

        <h1 class="header-xlarge">{{ title }}</h1>
        <p class="large">{{ subtitle }}</p>

        <div class="signup-form-container">
          <div class="input-wrap">
            <input class="form-input form-input_text" name="email" type="email" placeholder="Personal Email" v-model="email">
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text" name="password" type="password" placeholder="Create Password" v-model="password">
          </div>
          <div class="input-wrap">
            <input class="form-input form-input_text error" name="zipcode" type="text" placeholder="Zip Code" v-model="zip">
            <!-- <span class="error-text">Please enter a valid zipcode</span> -->
          </div>
          <div class="input-wrap text-centered">
            <input class="form-input form-input_checkbox" type="checkbox" id="checkbox" v-model="terms">
            <label class="form-label form-label_checkbox" for="checkbox">I agree to <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</label>
          </div>
        </div>

      </div>

      <div class="text-centered">
        <input type="submit" class="button" value="Sign Up">
      </div>
    </form>

    <!-- <form>
      <practitioner v-show="step === 1" />
      <phone v-show="step === 2" />
      <location v-show="step === 3" />
      <datetime v-show="step === 4" />
    </form> -->
  </div>
</template>

<script>
  export default {
    name: 'Signup',
    data() {
      return {
        title: 'Your journey starts here',
        // subtitle: 'Before talking to a doctor, we need some basic contact info, your choice of practitioner and a date/time you are available for a consultation. This should take less than 5 minutes.',
        subtitle: 'Create an account',
        email: '',
        password: '',
        zip: '',
        terms: false,
        validated: true,
      }
    },
    methods: {
      onSubmit() {
        console.log("form submitted");

        this.$router.push('/confirmation');

        // navigate to the interstitial


        // axios.post('api/v1/users', {
        //   email: 'test@test.com',
        //   terms: false,
        // })
        // .then(response => {
        //   console.log(reponse);
        // });
        // should be used to send rest of data to back-end
      },
    },
  }
</script>

<style>

</style>
