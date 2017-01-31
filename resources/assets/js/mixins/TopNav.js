import {capitalize} from '../filters/textformat.js';

export default {
    data() {
        return {
            nav_is_open: false
        }
    },
    methods: {
        capitalize,
        toggleNav() {
            this.nav_is_open = !this.nav_is_open;
        },
        logout() {
            this.$http.post('/logout').then(response => {
                location.href = '/';
            });
        },
        viewSignupPage() {
            if (typeof mixpanel !== 'undefined') mixpanel.track("View Sign Up Page");
        }
    }
}