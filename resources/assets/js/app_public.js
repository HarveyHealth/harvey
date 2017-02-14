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
        homepageLoaded: false,
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
            fatigue: {
                label: 'Fatigue',
                value: 3
            },
            aches: {
                label: 'Aches/pains',
                value: 3
            },
            allergies: {
                label: 'Allergies',
                value: 3
            },
            depression: {
                label: 'Depression',
                value: 3
            },
            digestion: {
                label: 'Digestion',
                value: 3
            },
            irritability: {
                label: 'Irritability',
                value: 3
            },
            libido: {
                label: 'Libido',
                value: 3
            },
            stress: {
                label: 'Stress',
                value: 3
            },
            weight: {
                label: 'Weight',
                value: 3
            },
            hair: {
                label: 'Hair, skin & nails',
                value: 3
            }
        },
        nav_is_inverted: true,
        isHomePage: false,
        wait: 400,
        nav_scroll_threshold: 56
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
        checkIsHomePage() {
            this.isHomePage = document.getElementsByTagName('body')[0].classList.contains('home');
        },
        onScroll(e) {
            if (window.pageYOffset > this.nav_scroll_threshold) {
                if (this.nav_is_inverted) this.nav_is_inverted = false;
            } else {
                if (!this.nav_is_inverted) this.nav_is_inverted = true;
            }
        },
        onPageScroll() {
            window.addEventListener('scroll', _.throttle(this.onScroll, this.wait), false);
        },
        offEvents() {
            window.removeEventListener('scroll');
        }
    },
    mounted() {
        this.checkIsHomePage();

        if (this.isHomePage) {
             this.$nextTick(() => {
                this.homepageLoaded = true;
            });
            this.onPageScroll();
        }
    },
    destroyed() {
        if (this.isHomePage) {
            this.offEvents();
        }
    }
}).$mount('#app');