<template>
  <div>
    <form @submit.prevent="onSubmit" v-show="!isComplete">
      <div class="container small">
        <img src="/images/signup/tree.png" class="registration-tree" alt="">

        <h1 class="header-xlarge">{{ title }}</h1>
        <p class="large">{{ subtitle }}</p>

        <div class="error-container" v-show="responseErrors.length > 0">
          <p v-for="error in responseErrors" v-text="error.detail" class="error-text"></p>
        </div>

        <div class="signup-form-container">

          <div class="input-wrap">
            <input class="form-input form-input_text error" name="zipcode" type="text" placeholder="Zip Code" v-model="zip" v-validate="{ required: true, digits: 5 }" data-vv-validate-on="blur" maxlength="5"/>
            <span v-show="errors.has('zipcode')" class="error-text">{{ errors.first('zipcode') }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text" name="email" type="email" placeholder="Personal Email" v-model="email" v-validate="'required|email'" data-vv-validate-on="blur" />
            <span v-show="errors.has('email')" class="error-text">{{ errors.first('email') }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text" name="password" type="password" placeholder="Create Password" v-model="password" v-validate="{ required: true, min: 6 }" data-vv-validate-on="blur" />
            <span v-show="errors.has('password')" class="error-text">{{ errors.first('password') }}</span>
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
        subtitle: '',
        email: '',
        password: '',
        zip: '',
        zipInRange: false,
        terms: false,
        isComplete: false,
        responseErrors: [],
        env: this.$parent.environment,
      }
    },
    components: {
      interstitial: Interstitial,
    },
    methods: {
      onSubmit() {

        // Validate the form
        this.$validator.validateAll().then(() => {

            // create the account
            axios.post('api/v1/users', {
              email: this.email,
              password: this.password,
              zip: this.zip,
              terms: this.terms,
            })
            .then(response => {
              this.login(this.email, this.password);
              // the form is complete
              this.isComplete = true;
              this.zipInRange = true;
            })
            .catch(error => {
              this.responseErrors = error.response.data.errors;
            });

        }).catch(() => {});
      },
        login(email, password) {
          axios.post('login', {
            email: email,
            password: password,
          })
          .catch(error => {
            // TODO: catch error
          });
        },
    },
    mounted () {
      if (this.env === 'prod') {
        this.$ma.trackEvent({
            action: 'Registration',
            fb_event: 'ViewContent',
            category: 'clicks',
            properties: {
                laravel_object: Laravel.user
            },
            value: 'PageView'
        })
      }
    }
  }
</script>
