import '../../bootstrap';
import router from './routes';

import Schedule from './Schedule.vue';

const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

const app = new Vue({
  router,
  components: {
    Schedule
  },
}).$mount('#schedule');
