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
