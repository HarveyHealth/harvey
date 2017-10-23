<template>
  <form @submit.prevent="onSubmit" :class="animClasses" v-if="!$root.$data.signup.completedSignup">
    
    <div class="signup-wrapper">

      <aside class="signup-quotes">
        <div class="quotes-icons">
          <div class="quotes-icons-top">
            <div><svg><use xlink:href="#apple" /></svg></div>
            <div><svg><use xlink:href="#stethoscope" /></svg></div>
            <div><svg><use xlink:href="#labs" /></svg></div>
            <div><svg class="smaller"><use xlink:href="#doctor" /></svg></div>
            <div class="show-xl"><svg><use xlink:href="#carrot" /></svg></div>
            <div class="show-xl"><svg class="with-stroke"><use xlink:href="#wellness" /></svg></div>
          </div>
          <div class="quotes-text">
            <div class="quotes-imgs">
              <a href="/"><svg class="harvey"><use xlink:href="#harvey-logo" /></svg></a>
            </div>
            <blockquote v-for="obj in quotes" :key="obj.source">
              <p v-html="'&ldquo;' + obj.quote + '&rdquo;'" class="copy-main font-lg"></p>
              <footer v-html="'&ndash; ' + obj.source" class="copy-muted font-italic"></footer>
            </blockquote>
          </div>
          <div class="quotes-icons-bottom">
            <div><svg><use xlink:href="#heart" /></svg></div>
            <div><svg class="smaller"><use xlink:href="#bottle" /></svg></div>
            <div><svg><use xlink:href="#baby" /></svg></div>
            <div><svg><use xlink:href="#scale" /></svg></div>
            <div class="show-xl"><svg class="smaller"><use xlink:href="#yoga" /></svg></div>
            <div class="show-xl"><svg><use xlink:href="#medicine" /></svg></div>
          </div>
        </div>
      </aside>

      <div class="container small signup-form-inputs">
        <div class="signup-container small naked signup">
          <h1 class="heading-1" v-html="title"></h1>
          <div class="input-wrap">
            <input class="form-input form-input_text" v-on:change="persistTextFields('first_name', signupData.first_name)" name="first_name" type="text" placeholder="First Name" v-model="signupData.first_name" v-validate="'required|alpha_spaces'" data-vv-as="First name" />
            <p v-show="errors.has('first_name')" class="copy-error">{{ firstNameError }}</p>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text" v-on:change="persistTextFields('last_name', signupData.last_name)" name="last_name" type="text" placeholder="Last Name" v-model="signupData.last_name" v-validate="'required|alpha_spaces'" data-vv-as="Last name" />
            <p v-show="errors.has('last_name')" class="copy-error">{{ lastNameError }}</p>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text" v-on:change="persistTextFields('email', signupData.email)" name="email" type="email" placeholder="Personal Email" v-model="signupData.email" v-validate="'required|email'" data-vv-validate-on="blur" />
            <p v-show="errors.has('email')" class="copy-error">{{ emailError }}</p>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text error" v-on:change="persistTextFields('zip', signupData.zip)" name="zip" type="text" placeholder="Zip Code" v-model="signupData.zip" v-validate="{ required: true, digits: 5 }" data-vv-validate-on="blur" maxlength="5"/>
            <p v-show="errors.has('zip')" class="copy-error">{{ zipError }}</p>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text" v-on:change="persistTextFields('password', signupData.password)" name="password" type="password" placeholder="Create Password" v-model="signupData.password" v-validate="{ required: true, min: 6 }" data-vv-validate-on="blur" />
            <p v-show="errors.has('password')" class="copy-error">{{ passwordError }}</p>
          </div>

          <div class="input-wrap last">
            <label class="form-label form-label_checkbox font-medium-gray" for="checkbox">
              <input class="form-input form-input_checkbox" v-model="terms" name="terms" type="checkbox" id="checkbox" v-validate="'required'"> I agree to <span class="is-hidden-mobile">Harvey's</span> <a href="/terms">terms</a> and <a href="/privacy">policies</a>.
            </label>
            <p v-show="errors.has('terms')" class="copy-error">{{ termsError }}</p>
          </div>

          <div class="font-centered">
            <button class="button button--blue" style="width: 160px" :disabled="isProcessing">
              <span v-if="!isProcessing">Sign Up</span>
              <LoadingGraphic v-else-if="isProcessing" :size="12" />
              <i v-else-if="isComplete" class="fa fa-check"></i>
            </button>
          </div>

        </div>
      </div>
    </div>
  </form>
</template>

<script>
import LoadingGraphic from '../../../commons/LoadingGraphic.vue';

export default {
  name: 'sign-up',
  components: {
    LoadingGraphic
  },
  data() {
    return {
      animClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
      },
      env: this.$root.$data.environment,
      isComplete: false,
      newsletter: false,
      isProcessing: false,
      quotes: [
        { quote: 'I can say without a shadow of a doubt, my Naturopathic Doctor gave me my life back.',
          source: 'Elizabeth Yorn (Missouri, battling Lupus)' }
      ],
      responseErrors: [],
      signupData: {
        email: localStorage.getItem('sign up email') || '',
        first_name: localStorage.getItem('sign up first_name') || '',
        last_name: localStorage.getItem('sign up last_name') || '',
        password: localStorage.getItem('sign up password') || '',
        terms: '',
        zip: localStorage.getItem('sign up zip') || '',
      },
      subtitle: '',
      terms: false,
      title: 'Your health journey<br>starts with us.',
      zipInRange: false,
    }
  },
  // These are necessary because VeeValidate's custom messages are just not working
  // http://vee-validate.logaretm.com/rules.html#field-sepecific-messages
  computed: {
    firstNameError() {
      if (this.errors.has('first_name')) {
        return this.errors.firstByRule('first_name', 'required')
          ? 'First name is required'
          : 'First name only takes alphabetic characters.'
      }
    },
    lastNameError() {
      if (this.errors.has('last_name')) {
        return this.errors.firstByRule('last_name', 'required')
          ? 'Last name is required'
          : 'Last name only takes alphabetic characters.'
      }
    },
    emailError() {
      if (this.errors.has('email')) {

        if (this.errors.firstByRule('email', 'inuse')) {
          return this.errors.firstByRule('email', 'inuse');
        } else {
          return this.errors.firstByRule('email', 'required')
            ? 'Email is required'
            : 'That is not a valid email address.'
        }
      }
    },
    zipError() {
      if (this.errors.has('zip')) {
        return this.errors.firstByRule('zip', 'required')
          ? 'Zipcode is required'
          : 'Zipcode must contain 5 numeric characters.'
      }
    },
    passwordError() {
      if (this.errors.has('password')) {
        return this.errors.firstByRule('password', 'required')
          ? 'Password is required'
          : 'Password needs minimum of 6 characters.'
      }
    },
    termsError() {
      if (this.errors.has('terms')) {
        return this.errors.firstByRule('terms', 'required')
          ? 'Please agree to terms and privacy policy.'
          : ''
      }
    }
  },
  methods: {
    onSubmit() {
      this.signupData.terms = this.terms ? true : '';
      // Validate the form
      this.$validator.validateAll(this.signupData).then(response => {

        if (!response) return;

        this.isProcessing = true;

        // create the user
        axios.post('api/v1/users', this.signupData)
          .then(response => {

            // log the user in
            this.login(this.signupData.email, this.signupData.password);
            this.isComplete = true;
            this.zipInRange = true;

            // Track successful signup
            if(this.$root.shouldTrack()) {
              // collect response information
              const userData = response.data.data.attributes;

              const userId = response.data.data.id || '';
              const firstName = userData.first_name || '';
              const lastName = userData.last_name || '';
              const email = userData.email || '';
              const zip = userData.zip || '';
              const city = userData.city || '';
              const state = userData.state || '';

              // Segment tracking
              analytics.track("Account Created");

              // Segment Identify
              analytics.identify(userId, {
                firstName: firstName,
                lastName: lastName,
                email: email,
                city: city,
                state: state,
                zip: zip,
              });
            }

            // remove local storage items on sign up
            // needed if you decide to sign up multiple acounts on one browser
            localStorage.removeItem('sign up email');
            localStorage.removeItem('sign up first_name');
            localStorage.removeItem('sign up last_name');
            localStorage.removeItem('sign up password');
            localStorage.removeItem('sign up zip');

          })
          // Error catch for user patch
          // The BE checks for invalid zipcodes based on states we know we cannot operate in
          // and also Iggbo servicing data.
          // If such a zipcode is entered, the users api will return a 400
          .catch(error => {
            if (error.response) {
              const errors = error.response.data.errors;
              const errorType = errors[0].type;
              const errorDetail = errors[0].detail;

              // Display error for email that is already in use
              if (errorType === 'email-in-use') {
                this.errors.add('email', errorDetail.message, 'inuse');
              }

              // If zip is out of range, track submission and route to interstitial.
              // Only do this if this is the only error in the form, though.
              else if (errorType === 'out-of-range') {
                // this is an out-of-range situation
                // track the failed signup
                if (this.$root.shouldTrack()) {
                  const firstName = this.signupData.first_name || '';
                  const lastName = this.signupData.last_name || '';
                  const email = this.signupData.email || '';
                  const zip = this.signupData.zip || '';

                  const outOfRangeState = errorDetail.state || '';
                  const outOfRangeCity = errorDetail.city || '';

                  analytics.track("Account Failed", {
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    city: outOfRangeCity,
                    state: outOfRangeState,
                    zip: zip,
                  });
                }

                this.$router.push({name: 'out-of-range', path: '/out-of-range'});
              }

              // reset the submission to allow for another attempt
              this.isProcessing = false;
              this.isComplete = false;
            }
          });

      // Error catch for vee-validate of signup form fields
      }).catch(error => {
        console.error('There are errors in the signup form fields.');
      });
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
        window.location.href = '/get-started';
      })
      .catch(error => {
        // TODO: catch error
      });
    },
    persistTextFields(field, value) {
      localStorage.setItem(`sign up ${field}`, value)
    },
    mounted () {
        this.$root.toDashboard();

        this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);

        if(this.$root.shouldTrack()) {
            analytics.page("Signup");
        }
    },
    beforeDestroy() {
        this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
    }
};
</script>
