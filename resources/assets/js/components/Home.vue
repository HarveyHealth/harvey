<template>
<div :class="['sections', {'has-scroll-behavior': triggerScrollBehavior}]" :style="showFooterStyle">
    <section v-for="(section, index) in sections"
        :id="section"
        :class="['section', {'current': currentSection == index}]"
    >
        <component :is="section"></component>
    </section>
    <footer-view></footer-view>
    <nav class="nav-sections">
        <ul>
            <li v-for="(section, index) in sections"
                :class="{'current': currentSection == index}"
            >
                <a @click="slide(index)"></a>
            </li>
        </ul>
    </nav>
</div>
</template>

<script>
import {throttle, debounce} from 'lodash';
import Symptoms from './Symptoms.vue';
import FooterView from './Footer.vue';

export default {
    name: 'home',
    components: {
        FooterView,
        intro: {
            template:
            `<div class="section-wrapper">
                <div class="container content is-medium">
                    <div class="has-text-centered">
                        <img
                            src="images/logos/main-logo-lg.png"
                            srcset="images/logos/main-logo-lg@2x.png 2x"
                            alt="Harvey Logo">
                        <h2 class="title is-4 font-secondary-family tagline">Medically trained. Naturally focused.</h2>
                    </div>
                    <p class="copy-has-max-width">Harvey is an online telehealth provider of naturopathic medicine.  Consult face-to-face with state-licensed naturopathic doctors without leaving the comfort of your home.</p>
                    <p class="copy-has-max-width">Harvey is powering the renaissance of complementary and alternative medicine by providing online access to naturopathic doctors and facilitating in-home blood draws for fast and convenient lab testing. We emphasize prevention, wellness and self-healing to combat acute and chronic conditions using evidence-based natural therapies.</p>
                </div>
            </div>`
        },
        comparison: {
            template:
            `<div class="section-wrapper">
                <div class="container content">
                    <h2 class="title is-2 font-secondary-family">What is naturopathic medicine?</h2>
                    <div class="image-wrapper"><img src="/images/home/naturopathic-medicine-graph.png" alt="Harvey"></div>
                    <p class="copy-has-max-width">Naturopathic medicine is a form of primary health care that emphasizes prevention and self-healing through the use of evidence-based natural therapies.</p>
                    <div class="columns has-text-centered">
                        <div class="column">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/naturopathic_1.png" alt="what is naturopathic medicine" />
                            </figure>
                            <p>An emphasis on disease prevention</p>
                        </div>
                        <div class="column">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/naturopathic_2.png" alt="what is naturopathic medicine" />
                            </figure>
                            <p>An individual approach to medicine</p>
                        </div>
                        <div class="column">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/naturopathic_3.png" alt="what is naturopathic medicine" />
                            </figure>
                            <p>Nutritional and holistic treatments</p>
                        </div>
                        <div class="column">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/naturopathic_4.png" alt="what is naturopathic medicine" />
                            </figure>
                            <p>Identifying the root cause of medical conditions</p>
                        </div>
                    </div>
                </div>
            </div>`
        },
        steps: {
            template:
            `<div class="section-wrapper">
                <div class="container content">
                    <h2 class="title is-2 font-secondary-family">How does Harvey work?</h2>
                    <p class="copy-has-max-width">Imagine finally being able to conquer your worse chronic symptoms and flourishing. We can help you in four specific ways.</p>
                    <div class="columns is-multiline">
                        <div class="column is-half columns is-mobile step-column-wrapper">
                            <div class="column homepage-icon">
                                <figure class="image is-96x96">
                                    <img src="/images/home/step_1.png" alt="how does harvey work step 1" />
                                </figure>
                                <span class="step-number">1</span>
                            </div>
                            <div class="column is-three-quarters">
                                <p class="title is-5"><strong>Consultation with a naturopath</strong></p>
                                <p>Consult with a licensed naturopathic doctor without leaving your home.</p>
                            </div>
                        </div>
                        <div class="column is-half columns is-mobile step-column-wrapper">
                            <div class="column homepage-icon">
                                <figure class="image is-96x96">
                                    <img src="/images/home/step_2.png" alt="how does harvey work step 2" />
                                </figure>
                                <span class="step-number">2</span>
                            </div>
                            <div class="column is-three-quarters">
                                <p class="title is-5"><strong>In-home blood draw</strong></p>
                                <p>In most cases, your doctor will recommend an in-home blood draw by a trained phlebotomist, saving you a trip to the clinic.</p>
                            </div>
                        </div>
                        <div class="column is-half columns is-mobile step-column-wrapper">
                            <div class="column homepage-icon">
                                <figure class="image is-96x96">
                                    <img src="/images/home/step_3.png" alt="how does harvey work step 3" />
                                </figure>
                                <span class="step-number">3</span>
                            </div>
                            <div class="column is-three-quarters">
                                <p class="title is-5"><strong>Health dashboards and analytics</strong></p>
                                <p>Within 7-14 days, we will send you a report  with highly accurate diagnostics of all the nutritional imbalances in your body.</p>
                            </div>
                        </div>
                        <div class="column is-half columns is-mobile step-column-wrapper">
                            <div class="column homepage-icon">
                                <figure class="image is-96x96">
                                    <img src="/images/home/step_4.png" alt="how does harvey work step 4" />
                                </figure>
                                <span class="step-number">4</span>
                            </div>
                            <div class="column is-three-quarters">
                                <p class="title is-5"><strong>Customized vitamin and nutrition plans</strong></p>
                                <p>Your doctor will recommend a regimen to perfectly optimize  your biomarkers and attack the root cause of your symptoms, naturally.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`
        },
        number: {
            template:
            `<div class="section-wrapper">
                <div class="container content">
                    <h2 class="title is-1 font-secondary-family">1,200,100</h2>
                    <p class="copy-has-max-width">The # of adults in United States and Canada who chose to receive treatment from a naturopathic doctor instead of a primary care physician in 2016. Maybe it’s time you talked to one about your health.</p>
                    <div class="columns is-multiline">
                        <div class="column is-half is-one-quarter-desktop">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/number_1.png" alt="" />
                            </figure>
                            <p>Naturopathic doctors take a highly personalized approach to medicine and treat all forms of health concerns — ranging from pediatric to geriatric, irritating symptoms to chronic illness, and physical to psychological.</p>
                        </div>
                        <div class="column is-half is-one-quarter-desktop">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/number_2.png" alt="" />
                            </figure>
                            <p>Naturopathic doctors are experts in diet, nutrition, vitamins, supplements, homeopathic and botanical medicine, and many other forms of complementary and alternative therapies.</p>
                        </div>
                        <div class="column is-half is-one-quarter-desktop">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/number_3.png" alt="" />
                            </figure>
                            <p>Naturopathic doctors graduate from an accredited four-year residential naturopathic medical school and passed an extensive board examination.</p>
                        </div>
                        <div class="column is-half is-one-quarter-desktop">
                            <figure class="homepage-icon image is-64x64">
                                <img src="/images/home/number_4.png" alt="" />
                            </figure>
                            <p>Naturopathic doctors must receive a license from their practicing state and complete 60 hours of continuing education every 2 years to maintain their license.</p>
                        </div>
                    </div>
                    <p class="copy-has-max-width"><strong>Harvey is making it possible to reduce the number of pharmaceutical drugs we put into our bodies and instead stimulate your body’s own natural healing abilities to combat the underlying root causes of preventable diseases, symptoms and conditions.</strong></p>
                </div>
            </div>`
        },
        symptoms: {
            template:
            `<div class="section-wrapper">
                <div class="container content">
                    <h2 class="title font-secondary-family">Tell us your symptoms</h2>
                    <div class="columns">
                        <p class="column is-one-quarter-widescreen copy-has-max-width">The majority of symptoms can be linked to imbalances in your biomarkers, such as vitamins, minerals, hormones, allergies and toxic heavy metals. Whatever it is, we will help get to the bottom of it. Simply tell us the severity of each of your sumptoms using the sliders below, then click “Save &amp; Get Started”.</p>
                        <symptoms class="column is-three-quarters-widescreen" :stats="stats" @changed="onChanged"></symptoms>
                    </div>
                    <p class="is-clearfix">
                        <small class="disclaimer">Your selection above will only be saved if you create an account on the next page.</small>
                        <button class="button is-primary is-medium is-pulled-right" @click="getStarted">Get Started</button>
                    </p>
                </div>
            </div>`,
            components: {
                Symptoms
            },
            data() {
                return {
                    changed: false,
                    stats: {
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
                    }
                }
            },
            methods: {
                onChanged() {
                    this.changed = true;
                },
                getStarted() {
                    if (this.changed) {
                        // user interacted with symptoms, save it to session storage
                        let formattedStats = Object.keys(this.stats)
                            .reduce( (ret, key) => {
                                ret[key] = this.stats[key].value;
                                return ret;
                            }, {} );

                        sessionStorage.setItem('symptoms', JSON.stringify(formattedStats));
                    }
                    location.href = '/signup';
                }
            }
        },
    },
    data() {
        return {
            triggerScrollBehavior: false,
            currentSection: 0,
            showFooter: false,
            wait: 1300,
            transitioning: false,
            whichTransitionEvent: this.whichTransitionEvent(),
            sections: ['intro', 'comparison', 'steps', 'number', 'symptoms']
        }
    },
    computed: {
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
        whichTransitionEvent() {
            let transitions = {
                'transition':'transitionend',
                'OTransition':'oTransitionEnd',
                'MozTransition':'transitionend',
                'WebkitTransition':'webkitTransitionEnd'
            }

            return transitions[ Modernizr.prefixed('transition') ];
        },
        checkScrollBehavior() {
            this.triggerScrollBehavior = Modernizr.mq('(min-width: 1192px)');
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
        this.checkScrollBehavior();
        this.onWindowResize();

        if (this.triggerScrollBehavior) {
            this.setCurrentSection();
            this.onPageScroll();
        }
    },
    destroyed() {
        this.offEvents();
    }
}
</script>

<style lang="sass" scoped>
$secondary: #5f7278;
$light-tan: #f8f3e6;
$opacity-dark: 0.8;
$tablet: 769px;
$desktop: 1000px;
$widescreen: 1192px;

@mixin from($device) {
    @media screen and (min-width: $device) {
        @content
    }
}

// scroll behavior starts from desktop
.has-scroll-behavior {
    .hero {
        background-color: transparent;
        &.is-fullheight {
            min-height: 100%;
        }
    }
    &.sections,
    .section,
    .footer {
        transition: cubic-bezier(1,.03,.42,.88) .6s;
    }
    &.sections {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
    .section {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        transform: translateY(-100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        &.current {
            transform: translateY(0);
            z-index: 1;
            ~ .section {
                transform: translateY(100%);
            }
        }
    }
    .footer {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
    }
    .nav-sections {
        position: fixed;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        li {
            &:not(:last-child) {
                margin-bottom: 5px;
            }
        }
        a {
            display: block;
            width: 14px;
            height: 14px;
            border: 2px solid $secondary;
            border-radius: 50%;
            transition: all .3s;
        }
        .current a {
            background-color: $secondary;
        }
    }
}
</style>