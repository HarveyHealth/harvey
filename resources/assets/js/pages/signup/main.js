import '../../bootstrap';
import router from '../routes';

import Signup from './Signup.vue'

const app = new Vue({
  router,
  components: {
    Signup
  },
}).$mount('#signup')
