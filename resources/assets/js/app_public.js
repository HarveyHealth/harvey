// This file defines interaction that happens on public pages.
// Specifically, it covers:
//     - top navbar
//     - home page
//         * symptoms selector
//         * tabs selector
//     - log in page
//     - register page

import './bootstrap';

// TRACKING
import VueMultianalytics from 'vue-multianalytics';
import initTracking from './vendors/tracking';

// HELPERS
import {throttle, debounce} from 'lodash';

// Forms handling, such as error handling, submit request, response handling, is extracted to a class
// it is used for log in, register form on the public pages
import Form from './utils/objects/Form.js';

// MIXINS
// TopNav includes top navbar behaviors and is shared between public and logged in pages
import TopNav from './utils/mixins/TopNav';

// COMPONENTS
// Below are componnents used on `resources/views/pages/homepage.blade.php`
import Symptoms from './pages/public/Symptoms.vue';
import VerticalTab from './commons/VerticalTab.vue';
import VerticalTabs from './commons/VerticalTabs.vue';

// for environment conditionals
const env = require('get-env')();

const app = new Vue({
    mixins: [TopNav],
    components: {
        Symptoms,
        VerticalTab,
        VerticalTabs
    },
    data: {
        guest: true,
        appLoaded: false,
        login: {
            form: new Form({
                email: '',
                password: '',
                remember: false,
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
        isHomePage: false,
        isLoginPage: false,
        wait: 400,
        navScrollThreshold: 56,
        showSignupContent: true
    },
    computed: {
        bodyClassNames() {
            return document.getElementsByTagName('body')[0].classList;
        }
    },
    methods: {
        // Passed as props to log in and register forms
        // in `resources/views/auth/login.blade.php` & `resources/views/auth/register.blade.php`
        onSubmit(e) {
            let target = e.target,
                formId = target.id,
                formMethod = target.method,
                formAction = target.action,
                formRedirectUrl = target.getAttribute('redirect-url');

            this[formId].form.submit(formMethod, formAction, this.onSuccess.bind(null, formRedirectUrl));
        },
        onSuccess(redirectUrl) {
            location.href = redirectUrl;

            if (formId == 'register') {
                if (typeof mixpanel !== 'undefined') mixpanel.track("New Signup");
            }
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
                    }, {} );
                try {
                    sessionStorage.setItem('symptoms', JSON.stringify(formattedStats));
                } catch(e) {}
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
        invertNavOnScroll(e) {
            if (window.pageYOffset > this.navScrollThreshold) {
                if (this.navIsInverted) this.navIsInverted = false;
            } else {
                if (!this.navIsInverted) this.navIsInverted = true;
            }
        },
        onPageScroll() {
            window.addEventListener('scroll', _.throttle(this.invertNavOnScroll, this.wait), false);
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

        this.isHomePage = this.checkWhichPage('home');

        if (this.isHomePage) {
            if (typeof mixpanel !== 'undefined') mixpanel.track("View Homepage");
            this.onPageScroll();

            initTracking();
            this.$ma.trackEvent({
                fb_event: 'PageView',
                type: 'product',
                category: 'clicks',
                properties: { laravel_object: Laravel.user }
            });
            this.$ma.trackEvent({
                action: 'Homepage',
                fb_event: 'ViewContent',
                type: 'product',
                properties: { laravel_object: Laravel.user },
            });
            console.log(`HOME`)
        }

        this.LoginPage = this.checkWhichPage('login');

        if (this.isLoginPage) {
            this.$ma.trackEvent({
                fb_event: 'PageView',
                type: 'product',
                category: 'clicks',
                properties: { laravel_object: Laravel.user }
            });
            console.log(`LOGIN`)
        }

        if (this.checkWhichPage('signup', 'register')) {
            if (typeof mixpanel !== 'undefined') mixpanel.track("View Sign Up Page");
            window.focus();
            window.addEventListener('blur', this.onIframeClick);
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
