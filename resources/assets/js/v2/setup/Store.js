import Vue from 'vue';
import moment from 'moment-timezone';

const env = require('get-env')();

export default new Vue({
    data: {
        // Global application state
        appointments: {
            all: [],
            index: {},
            upcoming: []
        },
        currentPage: null,
        grid: {
            s:     { width: 0,    classes: [] },
            ns:    { width: 420,  classes: [] },
            m:     { width: 640,  classes: [] },
            l:     { width: 780,  classes: [] },
            xl:    { width: 960,  classes: [] },
            xxl:   { width: 1280, classes: [] }
        },
        hasRequested: {
            allAppointments: false,
            allPractitioners: false,
            onePractitioner: false,
            practitionerAvailability: false,
            upcomingAppointments: false,
            userIntake: false
        },
        isLoading: {
            allAppointments: false,
            allPractitioners: false,
            onePractitioner: false,
            practitionerAvailability: false,
            upcomingAppointments: false,
            userIntake: false
        },
        isMobileMenuOpen: false,
        practitioners: {
            all: [],
            availability: [],
            index: {},
            licensed: []
        },
        user: window.Laravel.user,
        users: {
            intake: {
                all: null,
                self: null
            },
            practitioner: {}
        },
        zone: moment.tz.guess(),
        zoneAbbr: moment.tz(moment.tz.guess()).format('z'),

        // Global application constants
        API: '/api/v1/',
        APPOINTMENT_PENDING: 'pending',
        BREAKPOINTS: {
            s: 0,
            ns: 420,
            m: 640,
            l: 780,
            xl: 960,
            xxl: 1280
        },
        COLORS: {
            alt: '#EDA1A6',
            copy: '#4f6268',
            'gray-0': '#f9f9f9',
            'gray-1': '#f5f5f5',
            'gray-2': '#f2f2f2',
            'gray-3': '#fafafa',
            'gray-4': '#eeeeee',
            'gray-5': '#e4eaec',
            muted: '#8fa2a8',
            white: '#ffffff'
        },
        DEFAULT_USER_IMAGE: '/images/default_user_image.png',
        REGULATED_STATES: [
          'AK', 'CA', 'HI', 'OR', 'WA', 'AZ', 'CO', 'MT', 'UT', 'KS', 'MN', 'ND', 'CT', 'ME', 'MD', 'NH', 'VT', 'DC'
        ],
        SOCIAL_LINKS: [
            { class: 'fa fa-medium', href: 'https://www.goharvey.com/blog' },
            { class: 'fa fa-instagram', href: 'https://www.instagram.com/go_harvey' },
            { class: 'fa fa-facebook', href: 'https://www.facebook.com/goharveyapp' },
            { class: 'fa fa-twitter', href: 'https://twitter.com/goharveyapp' },
            { class: 'fa fa-youtube', href: 'https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw' }
        ],
        SPACING: [0, 0.25, 0.7, 1.3, 2, 4, 8, 16],
        SUPPORT: {
            email: 'support@goharvey.com',
            name: 'Sandra Walker',
            phone: '800-690-9989',
            available: 'Weekdays 9am - 6pm PST'
        }
    },

    // Global application computed
    computed: {
        isAdmin() { return this.user.user_type === 'admin'; },
        isNotAdmin() { return !this.isAdmin; },
        isDev() { return env === 'dev' || 'development'; },
        isLoggedIn() { return this.user.signedIn; },
        isStage() { return env === 'stage' || 'staging'; },
        isPatient() { return this.user.user_type === 'patient'; },
        isPractitioner() { return this.user.user_type === 'practitioner'; },
        isProd() { return env === 'prod' || 'production'; },

        upcomingAppointments() {
            return this.appointments.upcoming
                .filter(a => a.attributes.status === this.APPOINTMENT_PENDING);
        }
    }
});
