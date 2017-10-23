import {capitalize} from '../filters/textformat.js';

export default {
    data() {
        return {
            nav_is_open: false
        };
    },
    methods: {
        capitalize,
        toggleNav() {
            this.nav_is_open = !this.nav_is_open;
        },
        logout() {
            this.$http.post('/logout').then(() => {
                location.href = '/';
            });
        },
        isDescendant(parent, child) {
            let node = child.parentNode;
            while (node != null) {
                if (node == parent) {
                    return true;
                }
                node = node.parentNode;
            }
            return false;
        },
        onBlur(e) {
            if (e.relatedTarget === null || !this.isDescendant(e.target, e.relatedTarget)) {
                this.toggleNav();
            }
        }
    }
};
