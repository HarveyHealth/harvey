// This file defines interaction that happens on public pages.
// Specifically, it covers:
//     - top navbar
//     - home page
//         * symptoms selector
//         * tabs selector
//     - log in page
//     - register page

import './bootstrap';

// Forms handling, such as error handling, submit request, response handling, is extracted to a class
// it is used for log in, register form on the public pages
import Form from './utils/objects/Form.js';

// MIXINS
// TopNav includes top navbar behaviors and is shared between public and logged in pages
import TopNav from './utils/mixins/TopNav';

// COMPONENTS
// Below are componnents used on `resources/views/pages/homepage.blade.php`
import LoadingGraphic from './commons/LoadingGraphic.vue';
import Symptoms from './pages/public/Symptoms.vue';
import VerticalTab from './commons/VerticalTab.vue';
import VerticalTabs from './commons/VerticalTabs.vue';
import FacebookSignin from './v2/components/base/inputs/FacebookSignin';

// for environment conditionals
const env = require('get-env')();

const app = new Vue({
    mixins: [TopNav],
    components: {
        LoadingGraphic,
        FacebookSignin,
        Symptoms,
        VerticalTab,
        VerticalTabs
    },
    data: {
        hasZipValidation: localStorage.getItem('harvey_zip_validation'),
        guest: true,
        appLoaded: false,
        isProcessing: false,
        login: {
            form: new Form({
                email: '',
                password: '',
                remember: false
            })
        },
        register: {
            form: new Form({
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                password: '',
                terms: false
            })
        },
        symptomsChanged: false,
        symptomsSaving: false,
        symptomsStats: {
            cardiovascular: {
                label: 'Cardiovascular',
                description: 'Chest pain, dizziness, fainting, fevers, irregular heartbeat, shortness of breath, leg swelling, etc.',
                value: 3
            },
            digestive: {
                label: 'Digestive',
                description: 'Acid reflux, constipation, gas, diarrhea, heartburn, indigestion, bloating, stomach pain, cramps, etc.',
                value: 3
            },
            endocrine_hormonal: {
                label: 'Endocrine/Hormonal',
                description: 'Depression, fatigue, hot flashes, insomnia, mood swings, night sweats, stress, vaginal dryness, weight gain/loss, etc.',
                value: 3
            },
            dermatological: {
                label: 'Dermatological',
                description: 'Hair, skin and nails weakness, and other exocrine gland issues.',
                value: 3
            },
            immune: {
                label: 'Immune',
                description: 'Frequent colds, flus, cold sores, swollen lymph glands, and/or fighting known autoimmune diseases.',
                value: 3
            },
            musculo_skeletal: {
                label: 'Musculo-skeletal',
                description: 'Aches, muscle pain, body fatigue, loss of muscle control, etc.',
                value: 3
            },
            nervous: {
                label: 'Nervous',
                description: 'Headaches, migraines, numbness, tingling, tremors, etc.',
                value: 3
            },
            renal_urinary: {
                label: 'Renal/Urinary',
                description: 'Loss of bladder control, urinary tract infection, liver/kidney issues, etc.',
                value: 3
            },
            reproductive: {
                label: 'Reproductive',
                description: 'Impotence, loss of libido, pre/post-menopause, yeast infections, and other reproductive issues.',
                value: 3
            },
            respiratory: {
                label: 'Respiratory',
                description: 'Allergies, breathing problems, chronic cough/cold issues, bronchial inflammation, etc.',
                value: 3
            }
        },
        navIsInverted: true,
        isLoginPage: false,
        wait: 400,
        navScrollThreshold: 56,
        showSignupContent: true,
        guestEmail: '',
        emailCaptureError: 'Not a valid email address',
        emailCaptureClasses: {
          'error-text': true,
          'is-visible': false
        },
        emailCaptureSuccess: false
    },
    computed: {
        bodyClassNames() {
          return document.getElementsByTagName('body')[0].classList;
        },
        isHomePage() {
          return window.location.pathname === '/';
        },
        getStartedLink() {
          return this.hasZipValidation ? '/get-started' : '/conditions';
        }
    },
    methods: {
        facebookLogin(e) {
          e.preventDefault();
          window.location.href = '/auth/facebook';
        },
        onEmailCaptureSubmit() {
          this.emailCaptureClasses['is-visible'] = false;
          const passes = (/[^@]+@\w+\.\w{2,}/).test(this.guestEmail);
          if (passes) {
            const visitorData = {
              to: this.guestEmail,
              template: 'subscribe',
              _token: Laravel.app.csrfToken
            };
            axios.post('/api/v1/visitors/send_email', visitorData).then(() => {
              this.emailCaptureSuccess = true;
              if (this.shouldTrack()) {
                analytics.identify({
                  email: this.guestEmail
                });
              }
            }).catch(error => {
              if (error.response.status === 429) {
                this.emailCaptureError = 'Oops, we\'ve already registered that email.';
              } else {
                this.emailCaptureError = 'Oops, error sending email. Please contact support.';
              }
              this.emailCaptureClasses['is-visible'] = true;
            });
          } else {
            this.emailCaptureError = 'Oops, that is not a valid email address.';
            this.emailCaptureClasses['is-visible'] = true;
          }
        },
        // Passed as props to log in and register forms
        // in `resources/views/auth/login.blade.php` & `resources/views/auth/register.blade.php`
        onSubmit(e) {
            this.isProcessing = true;
            let target = e.target,
                formId = target.id,
                formMethod = target.method,
                formAction = target.action,
                formRedirectUrl = target.getAttribute('redirect-url');

            const cancelProcessing = () => this.isProcessing = false;

            this[formId].form.submit(formMethod, formAction, this.onSuccess.bind(null, formRedirectUrl), cancelProcessing);
        },
        onSuccess(redirectUrl) {
            location.href = redirectUrl;

            // if (formId == 'register') {
            //     if (typeof mixpanel !== 'undefined') mixpanel.track("New Signup");
            // }
        },
        // Symptoms selector
        onChanged() {
            this.symptomsChanged = true;
        },
        getStarted() {
            if (this.symptomsChanged) {
                // user interacted with symptoms, save it to session storage
                let formattedStats = Object.keys(this.symptomsStats)
                    .reduce( (ret, key) => {
                        ret[key] = this.symptomsStats[key].value;
                        return ret;
                    }, {});
                try {
                    sessionStorage.setItem('symptoms', JSON.stringify(formattedStats));
                } catch(e) {
                    sessionStorage.removeItem('symptoms');
                }
            }

            this.symptomsSaving = true;

            setTimeout(()=> {
                location.href = '/signup';
            }, 400);
        },
        checkWhichPage(...args) {
            let result = false;
            result = [...args].some(arg => {
                return this.bodyClassNames.contains(arg);
            });
            return result;
        },
        invertNavOnScroll() {
            if (window.pageYOffset > this.navScrollThreshold) {
                if (this.navIsInverted) this.navIsInverted = false;
            } else {
                if (!this.navIsInverted) this.navIsInverted = true;
            }
        },
        onIframeClick() {
            if(document.activeElement === document.querySelector('iframe')) {
                setTimeout(() => {
                    this.showSignupContent = false;
                }, 200);
                setTimeout(() => {
                    const bodyEl = document.getElementsByTagName('body')[0];
                    bodyEl.className += ' widget-on-focus';
                }, 500);
                window.removeEventListener('blur', this.onIframeClick);
            }
        },
        shouldTrack() {
          return env === 'production' || env === 'prod';
        },
        getUrlParams() {
          const url = window.location.search;
          if (!url) return null;

          return (/^[?#]/.test(url) ? url.slice(1) : url)
            .split('&')
            .reduce((params, param) => {
              let [key, value] = param.split('=');
              params[key] = value ? decodeURIComponent(value.replace(/\+/g, ' ')) : '';
              return params;
            }, {});
        }
    },
    mounted() {
        this.$nextTick(() => {
          this.appLoaded = true;
        });
        window.addEventListener('scroll', _.throttle(this.invertNavOnScroll, this.wait), false);

        // This is a temporary solution until we refactor how analytics is loaded
        // on public pages
        const path = window.location.pathname;

          let currentPage = '';

          if(this.isHomePage) {
            currentPage = 'Homepage';
          } else if (path === '/about') {
            currentPage = 'About';
          } else if (path === '/lab-tests') {
            currentPage = 'Lab Tests';
          }

          // send the page event
          if (this.shouldTrack()) {
            analytics.page(currentPage);

            // indentify and send along any url paramaters if they exist
            const parameterObject = this.getUrlParams();
            if(parameterObject !== null) {
                analytics.identify(parameterObject);
            }
        }
        if (env !== 'prod' || env !== 'production') {
          window.Root = this;
        }
    },
    destroyed() {
        if (this.isHomePage) {
            window.removeEventListener('scroll');
        } else if (this.checkWhichPage('signup', 'register')) {
            window.removeEventListener('blur', this.onIframeClick);
        }
    }
}).$mount('#app');

export default app;
