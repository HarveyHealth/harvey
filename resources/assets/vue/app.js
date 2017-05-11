import './bootstrap';
import router from './routes';
import VueMultianalytics from 'vue-multianalytics';


// FILTERS
import filter_datetime from './utils/filters/datetime';
import phonemask from './utils/directives/phonemask';

// MIXINS
import TopNav from './utils/mixins/TopNav';

// COMPONENETS
import Alert from './commons/Alert.vue';


Vue.filter('datetime', filter_datetime);
Vue.directive('phonemask', phonemask);

const env = require('get-env')();

// Centralized event handler to easily share among components
const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

eventHub.$on('animate', (classes, classname, state, delay) => {
  if (delay) {
    setTimeout(() => classes[classname] = state, delay)
  } else {
    classes[classname] = state
  }
});

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
    mixins: [TopNav],
    components: {
        Alert
    },
    data: {
        guest: false,
        global: {
            user: {},
            appointments: [],
            patients: [],
            test_results:[]
        },
        environment: env,
        apiUrl: '/api/alpha'
    },
    mounted() {
        Stripe.setPublishableKey(Laravel.services.stripe.key);

        this.$http.get(this.apiUrl + '/users/' + Laravel.user.id)
            .then( response => {
                this.global.user = response.data.data;
            } )
            .catch( error => this.global.user = {} );

        this.$eventHub.$on('mixpanel', (event) => {
            if (typeof mixpanel !== 'undefined') mixpanel.track(event);
        });
    }
}).$mount('#app');