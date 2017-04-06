import './bootstrap';

// helpers
import Form from './objects/Form.js';
import {throttle, debounce} from 'lodash';

// mixins
import TopNav from './mixins/TopNav';

// components
import Symptoms from './components/pages/Symptoms.vue';
import VerticalTab from './components/_includes/VerticalTab.vue';
import VerticalTabs from './components/_includes/VerticalTabs.vue';

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
        // log in and register forms
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
        // symptoms
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
        onScroll(e) {
            if (window.pageYOffset > this.navScrollThreshold) {
                if (this.navIsInverted) this.navIsInverted = false;
            } else {
                if (!this.navIsInverted) this.navIsInverted = true;
            }
        },
        onPageScroll() {
            window.addEventListener('scroll', _.throttle(this.onScroll, this.wait), false);
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
