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

// COMPONENTS
// Below are componnents used on `resources/views/pages/homepage.blade.php`
import LoadingGraphic from './commons/LoadingGraphic.vue';
import Symptoms from './pages/public/Symptoms.vue';
import VerticalTab from './commons/VerticalTab.vue';
import VerticalTabs from './commons/VerticalTabs.vue';
import { FacebookSignin } from 'inputs';
import { MainFooter, PublicNav } from 'nav';
import { GridStyles } from 'layout';

import Util from './v2/util';
import Config from './v2/config';

window.App = {};
App.Config = Config(Laravel);
App.Util = Util;
App.Public = {};
App.Public.State = {
    conditions: [],
    conditionSubText: [],
    conditionIconColors: ['is-lime', 'is-pink', 'is-brown', 'is-green', 'is-turquoise', 'is-slategrey', 'is-purple', 'is-ford'],
    misc: {
        grid: {
            s:     { width: 0,    classes: [] },
            ns:    { width: 420,  classes: [] },
            m:     { width: 640,  classes: [] },
            l:     { width: 780,  classes: [] },
            xl:    { width: 960,  classes: [] },
            xxl:   { width: 1280, classes: [] }
        }
    }
};

App.Public.setConditions = conditions => {
    App.Public.State.conditions = conditions;
};

Vue.prototype.Laravel = Laravel;
Vue.prototype.Config = App.Config;
Vue.prototype.Util = App.Util;

Vue.prototype.State = (path, ifUndefined) => {
  return App.Util.data.propDeep(path.split('.'), App.Public.State, ifUndefined);
};

const app = new Vue({
    components: {
        LoadingGraphic,
        FacebookSignin,
        GridStyles,
        MainFooter,
        PublicNav,
        Symptoms,
        VerticalTab,
        VerticalTabs
    },
    data: {
        State: App.Public.State,

        appClass: '',
        appLoaded: false,
        emailCaptureError: 'Not a valid email address',
        emailCaptureClasses: {
          'error-text': true,
          'is-visible': false
        },
        emailCaptureSuccess: false,
        guest: true,
        guestEmail: '',
        hasZipValidation: localStorage.getItem('harvey_zip_validation'),
        isLoginPage: false,
        isProcessing: false,
        login: {
            form: new Form({
                email: '',
                password: '',
                remember: false
            })
        },
        navIsInverted: true,
        navScrollThreshold: 56,
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
        showSignupContent: true,
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
        wait: 400
    },
    computed: {
        bodyClassNames() {
          return document.getElementsByTagName('body')[0].classList;
        },
        conditions() {
            return this.State.conditions;
        },
        getStartedLink() {
          if (Laravel.user.signedIn) {
            return Laravel.user.has_an_appointment
              ? { href: '/dashboard', display: `${this.userAvatar}<span>Dashboard</span>` }
              : { href: '/get-started', display: 'Get Started' };
          } else {
            return this.hasZipValidation
              ? { href: '/get-started', display: 'Get Started' }
              : { href: '/conditions', display: 'Get Started' };
          }
        },
        isHomePage() {
          return window.location.pathname === '/';
        },
        loginLink() {
          return Laravel.user.signedIn
            ? { href: '/logout', display: 'Log out' }
            : { href: '/login', display: 'Log in' };
        },
        userAvatar() {
          return `<img alt="" src="${Laravel.user.image_url}" class="top-nav-avatar" />`;
        }
    },
    methods: {
        facebookLogin() {
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

              analytics.identify({
                email: this.guestEmail
              });
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
        toggleMenu() {
            this.appClass = this.appClass ? '' : 'menu-is-open';
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
        }
    },
    mounted() {
        this.$nextTick(() => {
          this.appLoaded = true;
        });

        // indentify and send along any url paramaters if they exist
        const getUrlParams = () => {
            const url = window.location.search;
            if (!url) return null;

            return (/^[?#]/.test(url) ? url.slice(1) : url)
                .split('&')
                .reduce((params, param) => {
                    let [key, value] = param.split('=');
                    params[key] = value ? decodeURIComponent(value.replace(/\+/g, ' ')) : '';
                    return params;
                }, {});
        };

        const parameterObject = getUrlParams();
        if (parameterObject !== null) {
            analytics.identify(parameterObject);
        }

        window.addEventListener('scroll', _.throttle(this.invertNavOnScroll, this.wait), false);
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
