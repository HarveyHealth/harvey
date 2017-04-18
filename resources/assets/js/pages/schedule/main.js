import '../../bootstrap';
import router from './routes';

import Schedule from './Schedule.vue';
import Confirmation from './Confirmation.vue';

import filter_datetime from '../../filters/datetime';
Vue.filter('datetime', filter_datetime);

const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

// use store pattern to manage HOC model
var store = {
  state: {
    appointmentDate: null,
  },
}


import VueMultianalytics from 'vue-multianalytics'

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
  },
  components: {
    Schedule,
    Confirmation,
  },
}).$mount('#schedule');
