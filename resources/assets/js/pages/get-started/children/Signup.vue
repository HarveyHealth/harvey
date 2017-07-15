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
            <blockquote v-for="obj in quotes" :key="obj.source">
              <p v-html="'&ldquo;' + obj.quote + '&rdquo;'" class="font-medium font-dark-gray"></p>
              <footer v-html="'&ndash; ' + obj.source" class="font-base font-medium-gray"></footer>
            </blockquote>
          </div>
          <div class="quotes-icons-bottom">
            <div><svg><use xlink:href="#heart" /></svg></div>
            <div><svg class="smaller"><use xlink:href="#bottle" /></svg></div>
            <div><svg><use xlink:href="#baby" /></svg></div>
            <div><svg><use xlink:href="#scale" /></svg></div>
            <div class="show-xl"><svg class="smaller"><use xlink:href="#yogo" /></svg></div>
            <div class="show-xl"><svg><use xlink:href="#medicine" /></svg></div>
          </div>
        </div>
      </aside>

      <div class="container small signup-form-inputs">

        <div class="signup-container signup-form-container">

          <h1 class="font-large font-xlarge_md font-dark-gray" v-html="title"></h1>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('first_name', signupData.first_name)" name="first_name" type="text" placeholder="First Name" v-model="signupData.first_name" v-validate="'required|alpha'" />
            <span v-show="errors.has('first_name')" class="error-text">{{ firstNameError }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('last_name', signupData.last_name)" name="last_name" type="text" placeholder="Last Name" v-model="signupData.last_name" v-validate="'required|alpha'" />
            <span v-show="errors.has('last_name')" class="error-text">{{ lastNameError }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('email', signupData.email)" name="email" type="email" placeholder="Personal Email" v-model="signupData.email" v-validate="'required|email'" data-vv-validate-on="blur" />
            <span v-show="errors.has('email')" class="error-text">{{ emailError }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray error" v-on:change="persistTextFields('zip', signupData.zip)" name="zip" type="text" placeholder="Zip Code" v-model="signupData.zip" v-validate="{ required: true, digits: 5 }" data-vv-validate-on="blur" maxlength="5"/>
            <span v-show="errors.has('zip')" class="error-text">{{ zipError }}</span>
          </div>

          <div class="input-wrap">
            <input class="form-input form-input_text font-base font-darkest-gray" v-on:change="persistTextFields('password', signupData.password)" name="password" type="password" placeholder="Create Password" v-model="signupData.password" v-validate="{ required: true, min: 6 }" data-vv-validate-on="blur" />
            <span v-show="errors.has('password')" class="error-text">{{ passwordError }}</span>
          </div>

          <div class="input-wrap last">
            <input class="form-input form-input_checkbox" v-model="terms" name="terms" type="checkbox" id="checkbox" v-validate="'required'">
            <label class="form-label form-label_checkbox font-medium-gray" for="checkbox">I agree to <a href="/terms">terms</a> and <a href="/privacy">privacy policy</a>.</label>
            <span v-show="errors.has('terms')" class="error-text">{{ termsError }}</span>
          </div>

          <div class="text-centered">
            <button class="button button--blue" style="width: 160px" :disabled="isProcessing">
              <span v-if="!isProcessing">Sign Up</span>
              <LoadingBubbles v-else-if="isProcessing" :style="{ width: '12px', fill: 'white' }" />
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
      env: this.$root.$data.environment,
      isComplete: false,
      newsletter: false,
      isProcessing: false,
      quotes: [
        { quote: 'I can say without a shadow of doubt, my naturopathic doctor gave me my life back.',
          source: 'Elizabeth Yorn (battling Lupus)' }
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
      title: 'Your health journey starts here.',
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
          : 'First name only takes alphabetic characters'
      }
    },
    lastNameError() {
      if (this.errors.has('last_name')) {
        return this.errors.firstByRule('last_name', 'required')
          ? 'Last name is required'
          : 'Last name only takes alphabetic characters'
      }
    },
    emailError() {
      if (this.errors.has('email')) {
        return this.errors.firstByRule('email', 'required')
          ? 'Email is required'
          : 'Not a valid email address'
      }
    },
    zipError() {
      if (this.errors.has('zip')) {
        return this.errors.firstByRule('zip', 'required')
          ? 'Zipcode is required'
          : 'Zipcode must contain 5 numeric characters'
      }
    },
    passwordError() {
      if (this.errors.has('password')) {
        return this.errors.firstByRule('password', 'required')
          ? 'Password is required'
          : 'Password needs minimum of 6 characters'
      }
    },
    termsError() {
      if (this.errors.has('terms')) {
        return this.errors.firstByRule('terms', 'required')
          ? 'Please check terms and conditions'
          : ''
      }
    }
  },
  methods: {
    onSubmit() {
      this.signupData.terms = this.terms ? true : '';
      // Validate the form
      this.$validator.validateAll(this.signupData).then(response => {
        this.isProcessing = true;

        // create the user
        axios.post('api/v1/users', this.signupData)
          .then(response => {
            // log the user in
            this.login(this.signupData.email, this.signupData.password);
            this.isComplete = true;
            this.zipInRange = true;

            // Track successful signup
            if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
              // collect response information
              const userData = response.data.data.attributes;

              const userId = response.data.data.id || '';
              const firstName = userData.first_name || '';
              const lastName = userData.last_name || '';
              const email = userData.email || '';
              const zip = userData.zip || '';

              // Segment tracking
              analytics.track("Account Created");

              // Segment Identify
              analytics.identify(userId, {
                firstName: firstName,
                lastName: lastName,
                email: email,
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
          // The BE checks for invalid zipcodes based on states we know we cannot operate in
          // and also Iggbo servicing data.
          // If such a zipcode is entered, the users api will return a 400
          .catch(error => {
            this.responseErrors = error.response.data.errors;
            this.$router.push({name: 'out-of-range', path: '/out-of-range'});
          });

      })
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
  },
  mounted () {
    this.$root.toDashboard();

    this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);

    if (this.$root.$data.environment === 'production' || this.$root.$data.environment === 'prod') {
      analytics.page("Signup");
    }
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
  }
}
</script>
