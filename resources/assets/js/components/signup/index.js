import '../../bootstrap';
//
// // HELPERS
// import {throttle, debounce} from 'lodash';
//
// import Signup from './index.vue'
//
// Vue.component('signup', Signup);
//
// const app = new Vue({
//     el: '#signup',
//     render: h => h(Signup),
// });

import Signup from './index.vue'

new Vue({
  el: '#signup',
  data: {
    firstname: '',
    lastname: '',
    phonenumber: ''
  },
  render: h => h(Signup)
});
