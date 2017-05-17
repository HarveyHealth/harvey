import '../../bootstrap';
import router from './router';
import VueMultianalytics from 'vue-multianalytics'

import Signup from './Signup.vue';

// for environment conditionals
const env = require('get-env')();

let gaConfig = {
  appName: 'Harvey', // Mandatory
  appVersion: '0.1', // Mandatory
  trackingId: 'UA-89414173-1', // Mandatory
  debug: true, // Whether or not display console logs debugs (optional)
}

let mixpanelConfig = {
  token: '03bfcdd448c2ec06b61e442bc6eeef79'
}

let facebookConfig = {
  token: '170447220119877'
}

Vue.use(VueMultianalytics, {
  modules: {
    ga: gaConfig,
    mixpanel: mixpanelConfig,
    facebook: facebookConfig
  }
})


const app = new Vue({
  router,
  data: {
    environment: env,
  },
  components: {
    Signup
  },
}).$mount('#signup');