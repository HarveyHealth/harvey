import './bootstrap';

// helpers
import Form from './objects/Form.js';
import {throttle, debounce} from 'lodash';

// mixins
import TopNav from './mixins/TopNav';

// components
import Symptoms from './components/pages/Symptoms.vue';

const app = new Vue({
    mixins: [TopNav],
    components: {
        Symptoms
    },
    data: {
        guest: true,
        homepageLayoutResetEnds: false,
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
                label: 'Aches / pains',
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
                label: 'Digestion / stomach',
                value: 3
            },
            irritability: {
                label: 'Irritability / mood',
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
                label: 'Hair and skin',
                value: 3
            }
        },
        // below are for homepage scroll
        isHomePage: false,
        triggerScrollBehavior: false,
        currentSection: 0,
        showFooter: false,
        wait: 1300,
        transitioning: false,
        whichTransitionEvent: '',
        sections: []
    },
    computed: {
        // below are for homepage scroll
        sectionNums() {
            return this.sections.length;
        },
        showFooterStyle() {
            if (this.triggerScrollBehavior && this.showFooter) {
                let footerHeight = document.querySelector('footer').clientHeight;

                return {
                    transform: 'translateY(-' + footerHeight + 'px)'
                }
            } else {
                return {
                    transform: 'translateY(0)'
                }
            }
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
        // below are for homepage scroll
        checkIsHomePage() {
            this.isHomePage = document.getElementsByTagName('body')[0].classList.contains('home');
        },
        getSectionsArr() {
            let nodes = document.querySelectorAll('.section');
            this.sections = Object.keys(nodes)
                .map( (index) => {
                    return nodes[index].id;
                } );
        },
        pre() {
            if (this.currentSection != 0) {
                this.transitioning = true;

                if (this.showFooter) {
                    this.showFooter = false;
                } else {
                    this.currentSection -= 1;
                    this.setUrlHash();
                }
            }
        },
        next() {
            if (this.currentSection != this.sectionNums - 1) {
                this.transitioning = true;
                this.currentSection += 1;
                this.setUrlHash();
            } else {
                this.showFooter = true;
            }
        },
        slide(sectionNum) {
            if (sectionNum||sectionNum == 0) {
                this.showFooter = false;
                this.transitioning = true;
                this.currentSection = sectionNum;
                this.setUrlHash();
            }
        },
        getUrlHash() {
            return window.location.hash.slice(1);
        },
        setUrlHash(name) {
            window.location.hash = "#" + this.sections[this.currentSection];
        },
        setCurrentSection() {
            let hash = this.getUrlHash();

            if (hash) this.currentSection = this.sections.indexOf(hash);
        },
        onScroll(e) {
            if (!this.transitioning) {
                if (e.deltaY > 0) {
                    this.next();
                } else if (e.deltaY < 0) {
                    this.pre();
                }
            }
        },
        setWhichTransitionEvent() {
            let transitions = {
                'transition':'transitionend',
                'OTransition':'oTransitionEnd',
                'MozTransition':'transitionend',
                'WebkitTransition':'webkitTransitionEnd'
            }

            this.whichTransitionEvent = transitions[ Modernizr.prefixed('transition') ];
        },
        checkScrollBehavior() {
            this.triggerScrollBehavior = Modernizr.mq('(min-width: 1192px)');
            this.homepageLayoutResetEnds = true;
        },
        onPageScroll() {
            window.addEventListener('wheel', _.throttle(this.onScroll, this.wait, { 'trailing': false }), false);
            document.querySelector('.sections').addEventListener(this.whichTransitionEvent, function(e) {
                if (e.target && ( e.target.matches('.current') || e.target.matches('.sections') )) {
                    this.transitioning = false;
                }
            }.bind(this));
        },
        offEvents() {
            window.removeEventListener('resize');
            window.removeEventListener('wheel');
            document.querySelector('.sections').removeEventListener(this.whichTransitionEvent);                
        },
        onWindowResize() {
            window.addEventListener('resize', _.debounce(this.checkScrollBehavior, 300), false);
        }
    },
    mounted() {
        this.checkIsHomePage();

        if (this.isHomePage) {
            this.checkScrollBehavior();
            this.onWindowResize();

            if (this.triggerScrollBehavior) {
                this.setWhichTransitionEvent();
                this.getSectionsArr();
                this.setCurrentSection();
                this.onPageScroll();
            }
        }
    },
    destroyed() {
        if (this.isHomePage) {
            this.offEvents();
        }
    }
}).$mount('#app');