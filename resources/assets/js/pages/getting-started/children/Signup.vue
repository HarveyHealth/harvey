<template>
  <form @submit.prevent="onSubmit" :class="animClasses">
    <!-- <svg><use xlink:href="#apple" /></svg> -->

    <div class="signup-wrapper">
      <aside class="signup-quotes">
        <blockquote v-for="obj in quotes" :key="obj.source">
          <p v-html="obj.quote"></p>
          <footer v-html="obj.source"></footer>
        </blockquote>
      </aside>

      <div class="container small signup-form-inputs">

        <h1 class="font-large font-xlarge_md font-dark-gray" v-html="title"></h1>

        <div class="signup-form-container">

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('first_name', firstname)" name="first_name" type="text" placeholder="First Name" v-model="firstname" v-validate="'required|alpha'" />
            <span v-show="errors.has('first_name')" class="error-text">{{ errors.first('first_name')}}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('last_name', lastname)" name="last_name" type="text" placeholder="Last Name" v-model="lastname" v-validate="'required|alpha'" />
            <span v-show="errors.has('last_name')" class="error-text">{{ errors.first('last_name') }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('email', email)" name="email" type="email" placeholder="Personal Email" v-model="email" v-validate="'required|email'" data-vv-validate-on="blur" />
            <span v-show="errors.has('email')" class="error-text">{{ errors.first('email') }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray error" v-on:change="persistTextFields('zip', zip)" name="zipcode" type="text" placeholder="Zip Code" v-model="zip" v-validate="{ required: true, digits: 5 }" data-vv-validate-on="blur" maxlength="5"/>
            <span v-show="errors.has('zipcode')" class="error-text">{{ errors.first('zipcode') }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('password', password)" name="password" type="password" placeholder="Create Password" v-model="password" v-validate="{ required: true, min: 6 }" data-vv-validate-on="blur" />
            <span v-show="errors.has('password')" class="error-text">{{ errors.first('password') }}</span>
          </div>

          <div class="input-wrap last">
            <input class="form-input form-input_checkbox" name="terms" type="checkbox" id="checkbox" v-model="terms" v-validate="'required'">
            <label class="form-label form-label_checkbox font-medium-gray" for="checkbox">I agree to <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</label>
            <span v-show="errors.has('terms')" class="error-text">{{ errors.first('terms') }}</span>
          </div>

          <!-- <div class="input-wrap" style="margin-top: -20px;">
            <input class="form-input form-input_checkbox" name="newsletter" type="checkbox" id="newsletter" v-model="newsletter">
            <label class="form-label form-label_checkbox" for="checkbox">I would like to receive the Harvey newsletter.</label>
          </div> -->

          <p class="text-centered" style="margin-top: 30px;">Start your health journey today.</p>

          <div class="text-centered">
            <button class="button button--blue" style="width: 160px" :disabled="processing">
              <span v-if="!processing">Sign Up</span>
              <LoadingBubbles v-else-if="processing" :style="{ width: '16px', fill: 'white' }" />
              <i v-else-if="isComplete" class="fa fa-check"></i>
            </button>
          </div>

        </div>
      </div>

    </div> <!-- end signup-wrapper -->
  </form>
</template>

<script>

import LoadingBubbles from '../../../commons/LoadingBubbles.vue';

export default {
  name: 'sign-up',
  components: {
    LoadingBubbles
  },
  data() {
    return {
      animClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
      },
      email: localStorage.getItem('sign up email') || '',
      env: this.$root.$data.environment,
      firstname: localStorage.getItem('sign up first_name') || '',
      isComplete: false,
      lastname: localStorage.getItem('sign up last_name') || '',
      newsletter: false,
      password: localStorage.getItem('sign up password') || '',
      processing: false,
      quotes: [
        { quote: 'I can say without a shadow of doubt, my naturopathic doctor gave me my life back.',
          source: 'Jordan Yorn (battling Lupus)' }
      ],
      responseErrors: [],
      subtitle: '',
      terms: false,
      title: 'Let&rsquo;s get acquainted.',
      zip: localStorage.getItem('sign up zip') || '',
      zipInRange: false,
    }
  },
  methods: {
    onSubmit() {
      // Validate the form
      this.$validator.validateAll().then(() => {
        this.processing = true;

          // create the account
          axios.post('api/v1/users', {
            email: this.email,
            first_name: this.firstname,
            last_name: this.lastname,
            password: this.password,
            terms: this.terms,
            zip: this.zip,
          })
          .then(response => {
            this.login(this.email, this.password);
            // the form is complete
            this.isComplete = true;
            this.zipInRange = true;

            if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
              this.$ma.trackEvent({
                  fb_event: 'CompleteRegistration',
                  type: 'product',
                  action: 'Completed Signup',
                  category: 'clicks',
                  value: 50.00,
                  currency: 'USD',
                  properties: { laravel_object: Laravel.user }
              });
              ga('category', 'website');
              ga('action', 'Sign Up For Account');
            }

            // remove local storage items on sign up
            // needed if you decide to sign up multiple acounts on one browser
            localStorage.removeItem('sign up email');
            localStorage.removeItem('sign up first_name');
            localStorage.removeItem('sign up last_name');
            localStorage.removeItem('sign up password');
            localStorage.removeItem('sign up zip');

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
      .then(resp => {
        // TODO: check zip code to determine if out of range
        // If so, use localStorage to set a flag for out-of-range page
        localStorage.setItem('new_registration', 'true');
        window.location.href = '/getting-started';
      })
      .catch(error => {
        // TODO: catch error
      });
    },
    persistTextFields(field, value) {
      localStorage.setItem(`sign up ${field}`, value)
    },
  },
  mounted () {
    this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);
    if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
      this.$ma.trackEvent({
          fb_event: 'PageView',
          type: 'product',
          category: 'clicks',
          properties: { laravel_object: Laravel.user }
      });
      this.$ma.trackEvent({
          fb_event: 'InitiateCheckout',
          type: 'product',
          action: 'Start Signup',
          category: 'clicks',
          value: 50.00,
          currency: 'USD',
          properties: { laravel_object: Laravel.user }
      });
    }
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
