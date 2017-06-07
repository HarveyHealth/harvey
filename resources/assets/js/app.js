import './bootstrap';
import router from './routes';
import VueMultianalytics from 'vue-multianalytics';

// FILTERS
import filter_datetime from './utils/filters/datetime';

// DIRECTIVES
import phonemask from './utils/directives/phonemask';

// MIXINS
import TopNav from './utils/mixins/TopNav';

// COMPONENETS
import Alert from './commons/Alert.vue';
import Schedule from './pages/schedule/Schedule.vue';
import Dashboard from './pages/dashboard/Dashboard.vue';

// HELPERS
import combineAppointmentData from './utils/methods/combineAppointmentData';
import moment from 'moment-timezone';
import sortByLastName from './utils/methods/sortByLastName';

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
        Alert,
        Dashboard
    },
    data: {
        apiUrl: '/api/v1',
        appointmentData: null,
        environment: env,
        flyoutActive: false,
        guest: false,
        global: {
            appointments: [],
            loadingAppointments: true,
            loadingPatients: true,
            loadingPractitioners: true,
            patients: [],
            practitioners: [],
            recent_appointments: [],
            test_results:[],
            upcoming_appointments: [],
            user: {},
        },
        initialAppointment: {},
        initialAppointmentComplete: false,
        timezone: moment.tz.guess(),
    },
    methods: {
      getAppointments() {
        axios.get(`${this.apiUrl}/appointments?include=patient.user`)
          .then(response => {
            this.global.appointments = combineAppointmentData(response.data).reverse();
            this.global.loadingAppointments = false;
          }).catch(error => console.log(error.response));

        axios.get(`${this.apiUrl}/appointments?filter=upcoming&include=patient.user`)
          .then((response) => this.global.upcoming_appointments = response.data)
          .catch(error => console.log(error.response));

        axios.get(`${this.apiUrl}/appointments?filter=recent&include=patient.user`)
          .then((response) => this.global.recent_appointments = response.data)
          .catch(error => console.log(error.response));
      },
      getPatients() {
        axios.get(`${this.apiUrl}/patients?include=user`).then(response => {
          const include = response.data.included;
          response.data.data.forEach((obj, i) => {
            this.global.patients.push({
              id: obj.id,
              name: `${include[i].attributes.last_name}, ${include[i].attributes.first_name}`,
              email: include[i].attributes.email,
              phone: include[i].attributes.phone
            })
          });
          this.global.patients = sortByLastName(this.global.patients);
          this.global.loadingPatients = false;
        });
      },
      getPractitioners() {
        if (Laravel.user.userType !== 'practitioner') {
          axios.get(`${this.apiUrl}/practitioners?include=availability`).then(response => {
            this.global.practitioners = response.data.data.map(dr => {
              return { name: `Dr. ${dr.attributes.name}`, id: dr.id }
            });
            this.global.loadingPractitioners = false;
          })
        } else {
          axios.get(`${this.$root.apiUrl}/practitioners?include=availability`).then(response => {
            this.global.practitioners = response.data.data.filter(dr => {
              return dr.attributes.name === Laravel.user.fullName;
            }).map(obj => {
              return { name: `Dr. ${obj.attributes.name}`, id: obj.id };
            });
            this.global.loadingPractitioners = false;
          })
        }
      },
      getUser() {
        axios.get(`/api/v1/users/${Laravel.user.id}`)
          .then(response => {
            this.global.user = response.data.data;
          })
          .catch(error => this.global.user = {} );
      }
    },
    mounted() {
        Stripe.setPublishableKey(Laravel.services.stripe.key);

        // Initial GET requests
        if (Laravel.user.signedIn) {
          this.getUser()
          this.getAppointments();
          this.getPractitioners();
          if (Laravel.user.userType !== 'patient') this.getPatients();
        }

        // Event handlers
        this.$eventHub.$on('mixpanel', (event) => {
            if (typeof mixpanel !== 'undefined') mixpanel.track(event);
        });

        // Google Analytics
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-89414173-1', 'auto');
        ga('send', 'pageview');

    }
}).$mount('#app');
