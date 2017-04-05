import './bootstrap';

import Signup from './App.vue'

if (document.querySelector('#signup')) {
  new Vue({
    el: '#signup',
    render: h => h(Signup)
  });
}
