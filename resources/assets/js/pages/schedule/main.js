import '../../bootstrap';
import router from './routes';
import VueMultianalytics from 'vue-multianalytics'

import Schedule from './Schedule.vue';
import Confirmation from './Confirmation.vue';
import filter_datetime from '../../filters/datetime';
import phonemask from '../../directives/phonemask';

// for environment conditionals
const env = require('get-env')();

Vue.filter('datetime', filter_datetime);
Vue.directive('phonemask', phonemask);

const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

// custom animations uses eventHub for extensibility
// classes = the class object
// classname = the specific class to toggle
// state = whether the class is on or off
// delay = time in milliseconds before the toggle
eventHub.$on('animate', (classes, classname, state, delay) => {
  if (delay) {
    setTimeout(() => classes[classname] = state, delay)
  } else {
    classes[classname] = state
  }
});

// use store pattern to manage HOC model
var store = {
  state: {
    appointmentDate: null,
  },
}

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
    sharedState: store.state,
    environment: env,
  },
  components: {
    Schedule,
    Confirmation,
  },
}).$mount('#schedule');
