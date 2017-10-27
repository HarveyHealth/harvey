<template>
  <SlideIn :delay="400" class="height-100">
    <form @submit.prevent="onSubmit" v-if="!$root.$data.signup.completedSignup" class="pad-sm-sides max-width-xxl min-width-100 vertical-center-absolute margin-0a">
      <div class="Row-lg align-middle">
        <aside class="Column-lg-1of2 Column-xl-4of7 is-visible-lg space-children-sm">
          <div class="is-padding">
            <div class="signup-aside-icon-row">
              <span><svg><use xlink:href="#apple" /></svg></span>
              <span><svg><use xlink:href="#stethoscope" /></svg></span>
              <span><svg><use xlink:href="#labs" /></svg></span>
              <span><svg><use xlink:href="#doctor" /></svg></span>
              <span class="is-inline-xl"><svg><use xlink:href="#carrot" /></svg></span>
              <span class="is-inline-xl"><svg class="use-stroke"><use xlink:href="#wellness" /></svg></span>
            </div>
            <div class="signup-aside-text">
              <div class="logo-wrapper">
                <a href="/">
                  <SvgIcon class="MainNav_Logo" :id="'harvey-logo'" />
                </a>
              </div>
              <p class="font-xl color-white is-padding font-centered">Based on your answers, we're confident our Naturopathic Doctors can help improve your health condition!</p>
            </div>
            <div class="signup-aside-icon-row">
              <span><svg><use xlink:href="#heart" /></svg></span>
              <span><svg><use xlink:href="#bottle" /></svg></span>
              <span><svg><use xlink:href="#baby" /></svg></span>
              <span><svg><use xlink:href="#scale" /></svg></span>
              <span class="is-inline-xl"><svg><use xlink:href="#yoga" /></svg></span>
              <span class="is-inline-xl"><svg><use xlink:href="#medicine" /></svg></span>
            </div>
          </div>
        </aside>
        <div class="Column-lg-1of2 Column-xl-3of7 margin-0a max-width-md">
          <div class="signup-container signup-form-container space-children-md">
            <h1 class="heading-1 font-centered" v-html="title"></h1>

            <div class="input-wrap">
              <input class="form-input form-input_text"
                @change="Util.data.toStorage('first_name', State('getstarted.userPost.first_name'))"
                name="first_name" type="text" placeholder="First Name"
                v-model="State('getstarted.userPost').first_name"
                v-validate="'required|alpha_spaces'" data-vv-as="First name" />
              <p v-show="errors.has('first_name')" class="copy-error">{{ firstNameError }}</p>
            </div>

            <div class="input-wrap">
              <input class="form-input form-input_text"
                @change="Util.data.toStorage('last_name', State('getstarted.userPost.last_name'))"
                name="last_name" type="text" placeholder="Last Name"
                v-model="State('getstarted.userPost').last_name"
                v-validate="'required|alpha_spaces'" data-vv-as="Last name" />
              <p v-show="errors.has('last_name')" class="copy-error">{{ lastNameError }}</p>
            </div>

            <div class="input-wrap">
              <input class="form-input form-input_text"
                @change="Util.data.toStorage('email', State('getstarted.userPost.email'))"
                name="email" type="email" placeholder="Personal Email"
                v-model="State('getstarted.userPost').email"
                v-validate="'required|email'" data-vv-validate-on="blur" />
              <p v-show="errors.has('email')" class="copy-error">{{ emailError }}</p>
            </div>

            <div class="input-wrap">
              <input class="form-input form-input_text"
                @change="Util.data.toStorage('password', State('getstarted.userPost.password'))"
                name="password" type="password" placeholder="Create Password"
                v-model="State('getstarted.userPost').password"
                v-validate="{ required: true, min: 6 }" data-vv-validate-on="blur" />
              <p v-show="errors.has('password')" class="copy-error">{{ passwordError }}</p>
            </div>

            <div class="input-wrap last">
              <label class="form-label form-label_checkbox font-sm" for="checkbox">
                <input class="form-input form-input_checkbox"
                  name="terms" type="checkbox" id="checkbox"
                  v-model="State('getstarted.userPost').terms"
                  v-validate="'required'"
                  checked="checked" /> I agree to <span class="is-hidden-mobile">Harvey's</span> <a href="http://help.goharvey.com/legal/terms">terms</a> and <a href="http://help.goharvey.com/legal/privacy">policies</a>.
              </label>
              <p v-show="errors.has('terms')" class="copy-error">{{ termsError }}</p>
            </div>
            <div class="font-centered">
              <ButtonInput
                :isDisabled="isProcessing"
                :isDone="isComplete"
                :isProcessing="isProcessing"
                :onClick="() => false"
                :text="'Sign Up'"
                :width="'160px'"
              />
              <div class="Divider-text is-white" data-text="OR"></div>
              <FacebookSignin :type="'signup'" :on-click="facebookSignup" />
              <p class="is-padding font-xs"><em>We never share any financial or personal health information with Facebook. We only request from them your name and email.</em></p>
              <p class="font-sm"><a href="/conditions"><i class="fa fa-long-arrow-left margin-right-xs"></i>Update Location</a></p>
            </div>
          </div>
        </div>
      </div>
    </form>
  </SlideIn>
</template>

<script>
import { ButtonInput, FacebookSignin } from 'inputs';
import { SlideIn } from 'layout';
import { SvgIcon } from 'icons';
import LoadingGraphic from '../../../../../commons/LoadingGraphic.vue';

export default {
  name: 'sign-up',
  components: {
    ButtonInput,
    FacebookSignin,
    LoadingGraphic,
    SlideIn,
    SvgIcon
  },
  data() {
    return {
      env: this.$root.$data.environment,
      isComplete: false,
      newsletter: false,
      isProcessing: false,
      quotes: [
        { quote: 'I can say without a shadow of a doubt, my Naturopathic Doctor gave me my life back.',
          source: 'Elizabeth Yorn (Missouri, battling Lupus)' }
      ],
      responseErrors: [],
      subtitle: '',
      zipInRange: false
    };
  },
  // These are necessary because VeeValidate's custom messages are just not working
  // http://vee-validate.logaretm.com/rules.html#field-sepecific-messages
  computed: {
    firstNameError() {
      if (this.errors.has('first_name')) {
        return this.errors.firstByRule('first_name', 'required')
          ? 'First name is required.'
          : 'First name only takes alphabetic characters.';
      }
    },
    lastNameError() {
      if (this.errors.has('last_name')) {
        return this.errors.firstByRule('last_name', 'required')
          ? 'Last name is required.'
          : 'Last name only takes alphabetic characters.';
      }
    },
    emailError() {
      if (this.errors.has('email')) {

        if (this.errors.firstByRule('email', 'inuse')) {
          return this.errors.firstByRule('email', 'inuse');
        } else {
          return this.errors.firstByRule('email', 'required')
            ? 'Email is required.'
            : 'That is not a valid email address.';
        }
      }
    },
    passwordError() {
      if (this.errors.has('password')) {
        return this.errors.firstByRule('password', 'required')
          ? 'Password is required.'
          : 'Password needs minimum of 6 characters.';
      }
    },
    termsError() {
      if (this.errors.has('terms')) {
        return this.errors.firstByRule('terms', 'required')
          ? 'Please agree to terms and privacy policy.'
          : '';
      }
    },
    title() {
      return `Now serving patients<br/>in ${App.Util.misc.getState(this.State('getstarted.zipValidation.state'))}.`;
    }
  },
  methods: {
    facebookSignup(e) {
      e.preventDefault();
      if(!this.State('getstarted.userPost.terms')) {
        this.errors.add('terms', 'error', 'required');
        this.errors.first('terms:required');
      } else {
        window.location.href = `/auth/facebook?zip=${this.State('getstarted.userPost.zip')}`;
      }
    },
    onSubmit() {
      this.State('getstarted.userPost').terms = this.State('getstarted.userPost.terms') ? true : '';
      // Validate the form
      this.$validator.validateAll(this.State('getstarted').userPost).then(response => {

        if (!response) return;

        this.isProcessing = true;

        // create the user
        axios.post('api/v1/users', this.State('getstarted.userPost'))
          .then(response => {

            // log the user in
            this.login(this.State('getstarted.userPost.email'), this.State('getstarted.userPost.password'));
            this.isComplete = true;
            this.zipInRange = true;

            // Track successful signup
            if(App.Logic.misc.shouldTrack()) {
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
                zip: zip
              });
            }

            // remove local storage items on sign up
            // needed if you decide to sign up multiple acounts on one browser
            App.Util.data.killStorage(['first_name', 'last_name', 'email', 'password']);

          })
          // Error catch for user patch
          // The BE checks for invalid zipcodes based on states we know we cannot operate in
          // and also Iggbo servicing data.
          // If such a zipcode is entered, the users api will return a 400
          .catch(error => {
            if (error.response) {
              console.error(error.response);
              this.responseErrors = error.response.data.errors;
              const errorDetail = this.responseErrors[0].detail;
              const errorType = this.responseErrors[0].type;

              // check for different error type responses
              if(errorType === 'email-in-use') {
                this.errors.add('email', errorDetail, 'inuse');
                this.errors.first('email:inuse');

                // reset the submission to allow for another attempt
                this.isProcessing = false;
                this.isComplete = false;
              }
            }
          });

      // Error catch for vee-validate of signup form fields
      }).catch(() => {
        console.error('There are errors in the signup form fields.');
      });
    },
    login(email, password) {
      axios.post('login', {
        email: email,
        password: password
      })
      .then(() => {
        // TODO: check zip code to determine if out of range
        // If so, use localStorage to set a flag for out-of-range page
        localStorage.setItem('new_registration', 'true');
        window.location.href = '/get-started';
      })
      .catch(() => {
        // TODO: catch error
      });
    }
  },
  mounted () {
    this.$root.toDashboard();

    if(App.Logic.misc.shouldTrack()) {
      analytics.page("Signup");
    }
  }
};
</script>
