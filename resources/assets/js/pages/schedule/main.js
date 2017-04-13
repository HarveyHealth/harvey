import '../../bootstrap';
import router from './routes';

import Schedule from './Schedule.vue';

const app = new Vue({
  router,
  components: {
    Schedule
  },
}).$mount('#schedule');
