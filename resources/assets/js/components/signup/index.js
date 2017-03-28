import '../../bootstrap';

// HELPERS
import {throttle, debounce} from 'lodash';

import Registration from './Registration.vue'
import Signup from './index.vue'
import Phone from './Phone.vue'

Vue.component('signup', Signup);

const app = new Vue({
    el: '#signup',
    render: h => h(Signup),
});
