<template>
    <div class="sections" :style="showFooterStyle">
        <section v-for="(section, index) in sections"
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
                `<div class="hero-body">
                    <div class="container content has-text-centered">
                        <img src="images/logos/main-logo-lg.png" alt="Harvey Logo"></img>
                        <h2 class="title">Medically trained. Naturally focused.</h2>
                        <p>Harvey is an online telehealth provider of naturopathic medicine.  Consult face-to-face with state-licensed naturopathic doctors without leaving the comfort of your home.</p>
                        <p>Harvey is powering the renaissance of complementary and alternative medicine by providing online access to naturopathic doctors and facilitating in-home blood draws for fast and convenient lab testing. We emphasize prevention, wellness and self-healing to combat acute and chronic conditions using evidence-based natural therapies.</p>
                    </div>
                </div>`
            },
            comparison: {
                template:
                `<div class="hero-body">
                    <div class="container content has-text-centered">
                        <h2 class="title">What is naturopathic medicine?</h2>
                        <p>Naturopathic medicine is a form of primary health care that emphasizes prevention and self-healing through the use of evidence-based natural therapies.</p>
                    </div>
                </div>`
            },
            steps: {
                template:
                `<div class="hero-body">
                    <div class="container content has-text-centered">
                        <h2 class="title">How does Harvey work?</h2>
                        <p>Imagine finally being able to conquer your worse chronic symptoms and flourishing. We can help you in four specific ways.</p>
                    </div>
                </div>`
            },
            number: {
                template:
                `<div class="hero-body">
                    <div class="container content has-text-centered">
                        <h2 class="title">1,200,100</h2>
                        <p>The # of adults in United States and Canada who chose to receive treatment from a naturopathic doctor instead of a primary care physician in 2016. Maybe it’s time you talked to one about your health.</p>
                    </div>
                </div>`
            },
            symptoms: {
                template:
                `<div class="hero-body">
                    <div class="container content has-text-centered">
                        <h2 class="title">Tell us your symptoms</h2>
                        <p>The majority of symptoms can be linked to imbalances in your biomarkers, such as vitamins, minerals, hormones, allergies and toxic heavy metals. Whatever it is, we will help get to the bottom of it. Simply tell us the severity of each of your sumptoms using the sliders below, then click “Save &amp; Get Started”.</p>
                        <symptoms></symptoms>
                        <small>Your selection above will only be saved if you create an account on the next page.</small>
                        <button class="button is-primary">Get Started</button>
                    </div>
                </div>`,
                components: {
                    Symptoms
                }
            },
        },
        data() {
            return {
                currentSection: 0,
                showFooter: false,
                wait: 1200,
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
                if (this.showFooter) {
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
                    console.log('prev')
                    this.transitioning = true;

                    if (this.showFooter) {
                        this.showFooter = false;
                    } else {
                        this.currentSection -= 1;
                    }
                }
            },
            next() {
                if (this.currentSection != this.sectionNums - 1) {
                    console.log('next')
                    this.transitioning = true;
                    this.currentSection += 1;
                } else {
                    console.log('show footer')
                    this.showFooter = true;
                }
            },
            slide(sectionNum) {
                if (sectionNum||sectionNum == 0) {
                    console.log('go to ', sectionNum);
                    this.transitioning = true;
                    this.currentSection = sectionNum;
                }
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
            }
        },
        mounted() {
            window.addEventListener('wheel', _.throttle(this.onScroll, this.wait, { 'trailing': false }), false);
            document.querySelector('.sections').addEventListener(this.whichTransitionEvent, function(e) {
                if (e.target && ( e.target.matches('.current') || e.target.matches('.sections') )) {
                    this.transitioning = false;
                }
            }.bind(this));
        },
        destroyed() {
            window.removeEventListener('wheel');
            document.querySelector('.sections').removeEventListener(this.whichTransitionEvent);
        }
    }
</script>

<style lang="sass" scoped>
    .sections,
    .section,
    .footer {
        transition: cubic-bezier(1,.03,.42,.88) .6s;
    }
    .footer {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
    }
    .sections {
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
        &.current {
            transform: translateY(0);
            ~ .section {
                transform: translateY(100%);
            }
        }
    }
    .nav-sections {
        position: fixed;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        li {
            &:not(:last-child) {
                margin-bottom: 5px;
            }
        }
        a {
            display: block;
            width: 14px;
            height: 14px;
            border: 2px solid #666;
            border-radius: 50%;
            transition: all .3s;
        }
        .current a {
            background-color: #666;
        }
    }
</style>