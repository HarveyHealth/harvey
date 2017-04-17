import '../../bootstrap';
import router from './routes';

import Schedule from './Schedule.vue';

import filter_datetime from '../../filters/datetime';
Vue.filter('datetime', filter_datetime);

const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

const app = new Vue({
  router,
  components: {
    Schedule
  },
}).$mount('#schedule');
