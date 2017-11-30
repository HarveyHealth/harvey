<template>
    <div :class="wrapClasses">
        <div class="nav-overlay"></div>
        <div class="nav-container">
            <div class="nav-content">
                <button class="nav-hamburger" @click="handleMenuClick()">
                    <i :class="hamburgerClasses"></i>
                </button>
                <a href="/" class="nav-logo" v-if="hasLogo">
                    <LogoIcon :alwaysShowText="keepLogoText" :hasDarkIcon="hasDarkLogo" :hasDarkText="hasDarkLogo" revealText />
                </a>
                <div class="nav-links" v-if="hasLinks">
                    <a href="/about">About</a>
                    <a href="/lab-tests">Labs</a>
                    <a href="/#conditions" @click="handleMenuClick('conditions')">Conditions</a>
                    <a v-if="!showDashboard" href="/login">Log In</a>
                </div>
                <div class="nav-right">
                    <div class="nav-phone dim" v-if="hasPhone">
                        <a href="tel:800-690-9989">(800) 690-9989</a>
                    </div>
                    <div class="nav-start" v-if="hasStart">
                        <a v-if="showDashboard" class="dim" href="/dashboard">
                            <img class="top-nav-avatar" :src="Laravel.user.image_url" />
                            <span>Dashboard</span>
                        </a>
                        <a v-else href="/#conditions" class="dim" @click="handleMenuClick('conditions')">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
        <div :class="spaceClasses"></div>
    </div>
</template>

<script>
import { LogoIcon } from 'icons';

export default {
    components: {
        LogoIcon
    },
    props: {
        // mobile hamburger menu will be disabled
        disableMobile: { type: Boolean, default: false },

        // Keep navigation colors (logo, links, buttons) in their dark color even
        // when scrollY is 0
        forceDark: { type: Boolean, default: false },

        // Create space between the nav and the proceeding content to compensate
        // for the fixed positioning
        giveSpace: { type: Boolean, default: false },

        // render the links container
        hasLinks: { type: Boolean, default: false },

        // render the logo
        hasLogo: { type: Boolean, default: false },

        // render the phone button
        hasPhone: { type: Boolean, default: false },

        // render the Get Started button
        hasStart: { type: Boolean, default: false },

        // on scroll, stick the nav to the top of the viewport
        isSticky: { type: Boolean, default: false },

        // logo text will not be hidden at mobile
        keepLogoText: { type: Boolean, default: false },

        // onMenuClick is necessary so public pages can toggle the menu class
        // on #app. This functionality is inherently built into the v2 architecture
        // so it is not necessary in those contexts where App is available
        onMenuClick: { type: Function }
    },
    data() {
        return {
            isMenuActive: false,
            isNavSolid: false,
            scrollDistance: 30,
            throttleTime: 200,
            yScroll: null
        };
    },
    computed: {
        hamburgerClasses() {
            return `fa ${this.isMenuActive ? 'fa-close' : 'fa-bars'}`;
        },
        hasDarkLogo() {
            return this.isNavSolid || (this.forceDark && !this.isMenuActive);
        },
        showDashboard() {
            const isSignedIn = Laravel.user.signedIn;
            const appointment = Laravel.user.has_an_appointment;
            const notPatient = Laravel.user.user_type !== 'patient';

            return isSignedIn && (appointment || notPatient);
        },
        spaceClasses() {
            return { 'nav-top-space': true, 'is-active': this.giveSpace };
        },
        wrapClasses() {
            return {
                'nav-wrap': true,
                'nav-is-dark': this.forceDark,
                'nav-is-mobile': !this.disableMobile,
                'nav-is-solid': this.isNavSolid,
                'nav-is-sticky': this.isSticky,
                'menu-is-active': this.isMenuActive
            };
        }
    },
    methods: {
        handleMenuClick(pageId) {
            if (pageId) {
                const pageSection = document.getElementById(pageId);
                if (pageSection) this.yScroll = pageSection.offsetTop;
            }

            if (this.isMenuActive) {
                this.menuClick();
                Vue.nextTick(() => {
                    window.scroll(0, this.yScroll);
                });
            } else {
                this.yScroll = window.scrollY;
                setTimeout(this.menuClick, 200);
            }

            this.isMenuActive = !this.isMenuActive;
        },

        handleNavOnScroll() {
            const isScreenWide = window.innerWidth > 780;
            const isPastScroll = window.pageYOffset > this.scrollDistance;

            if (isPastScroll && isScreenWide && !this.isNavSolid) {
                this.isNavSolid = true;
            } else if (!isPastScroll && this.isNavSolid) {
                this.isNavSolid = false;
            }
        },

        menuClick() {
            if (this.onMenuClick) {
                this.onMenuClick();
            } else {
                App.Logic.misc.toggleMobileMenu(this.State('misc.isMobileMenuOpen'));
            }
        }
    },
    mounted() {
        window.foo = () => this;
        if (this.isSticky) {
            window.addEventListener('scroll', _.throttle(this.handleNavOnScroll, this.throttleTime), false);
        }
    },
    destroyed() {
        if (this.isSticky) {
            window.removeEventListener('scroll');
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    // Main wrap
    .nav-wrap {
        margin: 0 auto;
        max-width: 1152px;
        position: relative;
        z-index: 1;

        body[class*="404"] &,
        .login &,
        .password-reset & {
            display: none;
        }
    }

    // Some contexts may need top spacing to compensate for the fixed navigation bar
    .nav-top-space.is-active {
        height: 0;

        @include query(lg) {
            height: 70px;
        }

        .nav-is-mobile & {
            height: 70px;
        }
    }

    // Container wrapper is used for solid nav on scroll
    @include query(lg) {
        .nav-container {
            background: transparent;
            height: 70px;
            left: 0;
            position: absolute;
            transition: background 200ms ease-in-out;
            width: 100%;

            .nav-is-sticky & {
                position: fixed;
            }

            .nav-is-solid & {
                background: rgba(255,255,255,0.9);
                border-bottom: 1px solid $color-gray-1;
            }
        }
    }

    // Content wrapper controls layout of items
    .nav-content {
        // styles without mobile and at lg breakpoint
        left: 24px;
        max-width: 1152px;
        position: relative;
        width: calc(100% - 48px);

        .nav-is-mobile & {
            position: absolute;

            @include query-up-to(lg) {
                left: 0;
                padding: 0 24px;
                width: 100%;
            }
            @include query(lg) {
                left: 24px;
                max-width: 1152px;
                position: relative;
                width: calc(100% - 48px);
            }
        }
        .menu-is-active & {
            @include query-up-to(lg) {
                bottom: 0;
                overflow: auto;
                padding-bottom: 60px;
                position: fixed;
                top: 0;
            }
        }

        // Hardcoded in public css
        @media screen and (min-width: 1192px) {
            left: 0;
            margin: 0 auto;
            padding: 0 6px;
            width: 100%;
        }
    }

    // Overlay for mobile menu
    .nav-overlay {
        display: none;

        .nav-is-mobile & {
            background: rgba($color-copy, 0);
            display: block;
            transition: background 200ms ease-in-out;

            @include query(lg) {
                display: none;
            }
        }

        .menu-is-active & {
            background: rgba($color-copy, 0.97);
            background-size: cover;
            background-position: center;
            bottom: 0;
            left: 0;
            position: fixed;
            top: 0;
            right: 0;
        }
    }

    // Fix the logo in place on mobile menu
    @include query-up-to(lg) {
        .nav-is-mobile.menu-is-active .logo {
            position: fixed;
        }
    }

    // Navigation hamnurger button
    .nav-hamburger {
        display: none;

        .nav-is-mobile & {
            @include query-up-to(lg) {
                background: rgba(255,255,255,0.4);
                border: 0;
                border-radius: 50%;
                color: $color-copy;
                cursor: pointer;
                display: block;
                height: 42px;
                outline: none;
                padding: 12px;
                position: fixed;
                right: 18px;
                transition: background 200ms ease-in-out;
                top: 12px;
                width: 42px;
                -webkit-appearance: none;

                &:hover {
                    background: rgba(255,255,255,0.8);
                }

                .fa {
                    font-size: 1rem;
                }

                .menu-is-active & {
                    background: transparent;
                    color: white;
                }
            }
        }
    }

    .nav-phone,
    .nav-start {
        display: inline-block;

        .nav-is-mobile & {
            @include query-up-to(lg) {
                display: none;
            }
        }
        .menu-is-active & {
            @include query-up-to(lg) {
                display: block;
            }
        }
    }

    .nav-right {
        position: absolute;
        right: 0;
        top: 22px;
    }

    .nav-is-mobile .nav-right {
        position: static;

        @include query(lg) {
            position: absolute;
            right: 0;
            top: 22px;
        }
    }

    .nav-links {
        display: none;

        @include query-up-to(lg) {
            font-size: 1.2rem;
            margin-top: 80px;
            text-align: center;

            .menu-is-open & {
                display: block
            }
        }
        @include query(lg) {
            top: -20px;
            position: relative;
            display: inline-block;
        }
        @include query(xl) {
            left: 48px;
        }
    }

    .nav-links a {
        color: white;
        -webkit-font-smoothing: antialiased;

        @include query-up-to(lg) {
            display: block;
            padding: .5rem;
        }
        @include query(lg) {
            display: inline-block;
            font-size: 15px;
            font-weight: 500;
            margin: 12px 0;
            padding: 10px;

            .nav-is-solid &,
            .nav-is-dark & {
                color: $color-copy;
            }

            &:hover {
                opacity: 0.7;
            }
        }
        @include query(xl) {
            font-size: 18px;
        }
    }

    .nav-phone a,
    .nav-start a {
        border: 1px solid;
        border-radius: 4px;
        color: white;
        font-size: 14px;
        margin-left: 12px;
        padding: 8px 10px;
        text-align: center;

        .nav-is-mobile & {
            @include query-up-to(lg) {
                bottom: 12px;
                font-size: 16px;
                margin-left: 0;
                padding: 12px;
                position: fixed;
                width: calc(50% - 18px);
            }
        }
        @include query(xl) {
            font-size: 16px;
            padding: 10px 16px;
        }
    }

    .nav-phone a {
        border-color: white;

        .nav-is-solid &,
        .nav-is-dark & {
            border-color: $color-copy;
            color: $color-copy;
        }

        .nav-is-mobile & {
            @include query-up-to(lg) {
                background: $color-copy;
                left: 12px;
            }
        }
    }

    .nav-start a {
        background: $turquoise;
        border-color: $turquoise;

        .nav-is-mobile & {
            @include query-up-to(lg) {
                right: 12px;
            }
        }
    }

    // For logged-in users
    .top-nav-avatar {
        border-radius: 50%;
        height: 20px;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;

        + span {
            display: inline-block;
            margin-left: 28px;
        }
    }
</style>
