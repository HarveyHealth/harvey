import './bootstrap';
import router from './routes';

// FILTERS
import filter_datetime from './utils/filters/datetime';

// DIRECTIVES
import VeeValidate from 'vee-validate';

// MIXINS
import TopNav from './utils/mixins/TopNav';

// COMPONENETS
import Alert from './commons/Alert.vue';
import Dashboard from './pages/dashboard/Dashboard.vue';
import Usernav from './commons/UserNav.vue';

// HELPERS
import combineAppointmentData from './utils/methods/combineAppointmentData';
import moment from 'moment-timezone';
import sortByLastName from './utils/methods/sortByLastName';

Vue.filter('datetime', filter_datetime);
Vue.use(VeeValidate);

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

import Config from './v2/config';
import Filters from './v2/filters';
import Http from './v2/http';
import Logic from './v2/logic';
import State from './v2/state';
import Util from './v2/util';

window.App = {};
App.Config = Config(Laravel);
App.Filters = Filters;
App.Http = Http;
App.Logic = Logic;
App.Util = Util;

// Register global filters
Vue.filter('formatPhone', Filters.formatPhone);
Vue.filter('fullName', App.Util.misc.fullName);

// Adding these objects to the Vue prototype makes them available from
// within Vue templates directly, cutting back on our use of computed
// properties, component props, and placeholder data.
Vue.prototype.Config = App.Config;
Vue.prototype.Http = App.Http;
Vue.prototype.Logic = App.Logic;
Vue.prototype.Util = App.Util;

// Turning State into a function allows you to query global state within
// Vue templates, providing default values to fall back on if a particular
// property is undefined. This is helpful when awaiting data structures from
// api calls. NOTE: this should be used as READ ONLY function.
Vue.prototype.State = (path, ifUndefined) => {
  return App.Util.data.propDeep(path.split('.'), App.State, ifUndefined);
}

const app = new Vue({
    router,
    mixins: [TopNav],
    components: {
        Alert,
        Dashboard,
        Usernav,
    },
    data: {
        // Adding State to the root data object makes it globally reactive.
        // We do not attach this to window.App for HIPPA compliance. Because of this,
        // any method that needs to access global state MUST be invoked with .call
        // and state should be referenced inside as this.$root.$data.State.
        //    Example:
        //      export default function hi(person) {
        //        const Store = this.$root.$data.State;
        //        return `Hi, ${Store[person]}.`
        //      }
        //      App.Logic.hi.call(this, 'person');
        State: State,

        apiUrl: '/api/v1',
        appointmentData: null,
        colors: {
          copy: '#4f6268'
        },
        clientList: [],
        permissions: Laravel.user.user_type,
        environment: env,
        currentUserId: Laravel.user.id,
        flyoutActive: false,
        guest: false,
        stripe: null,
        global: {
            appointments: [],
            confirmedDoctors: [],
            confirmedPatients: [],
            currentPage: '',
            creditCards: [],
            detailMessages: {},
            loadingAppointments: true,
            loadingCreditCards: true,
            loadingClients: true,
            loadingPatients: true,
            loadingPractitioners: true,
            practitionerProfileLoading: true,
            loadingLabOrders: true,
            loadingMessages: true,
            loadingLabTests: true,
            loadingTestTypes: true,
            loadingUser: true,
            loadingUserEditing: true,
            menuOpen: false,
            messages: [],
            patients: [],
            practitioners: [],
            recent_appointments: [],
            // Updated: 08/22/2017
            // This is a hotfix and should be included in the backend logic when determining which
            // practitioners to send to the frontend
            regulatedStates: [
              'AK', 'CA', 'HI', 'OR', 'WA', 'AZ', 'CO', 'MT', 'UT', 'KS', 'MN', 'ND', 'CT', 'ME', 'MD', 'MA', 'NH', 'PA', 'VT', 'DC'
            ],
            signed_in: Laravel.user.signedIn,
            test_results: [],
            upcoming_appointments: [],
            unreadMessages: [],
            confirmedDoctors: [],
            confirmedPatients: [],
            labOrders: [],
            labTests: [],
            patientLookUp: {},
            practitionerLookUp: {},
            user: {},
            selfPractitionerInfo: null,
            user_editing: {}
        },
        signup: {
          availability: [],
          availableTimes: [],
          billingConfirmed: false,
          cardBrand: '',
          cardCvc: '',
          cardExpiration: '',
          cardName: '',
          cardNumber: '',
          cardLastFour: '',
          code: '',
          completedSignup: false,
          codeConfirmed: false,
          cost: '',
          data: {
            appointment_at: null,
            reason_for_visit: 'First appointment',
            practitioner_id: null,
          },
          googleMeetLink: '',
          phone: '',
          phonePending: false,
          phoneConfirmed: false,
          practitionerName: '',
          practitionerState: '',
          selectedDate: null,
          selectedDay: null,
          selectedPractitioner: 0,
          selectedWeek: null,
          selectedTime: null,
          visistedStages: [],
        },
        initialAppointment: {},
        initialAppointmentComplete: false,
        labTests: {},
        timezone: moment.tz.guess(),
        timezoneAbbr: moment.tz(moment.tz.guess()).format('z')
    },
    methods: {
        addTimezone(value) {
            if (value) return `${value} (${this.timezoneAbbr})`;
            else return this.timezoneAbbr;
        },
        // Filters patient list for practitioners according to state licensing regulations
        // patients = patient list
        // states = array of states practitioner is licensed in
        filterPatients(patients, states) {
          const stateList = states.map(s => s.state);
          return patients.filter(patient => {
            if (this.global.regulatedStates.indexOf(patient.state) > -1) {
              return stateList.indexOf(patient.state) > -1;
            } else {
              return true;
            }
          })
        },
        // Filters practitioner list by state licensing regulations
        // practitioners = practitioner list from backend or from appointments page
        // state = user state to test against
        filterPractitioners(practitioners, state) {
          return practitioners.filter(practitioner => {
            // First check if the user's state is regulated or not
            const userRegulatedState = this.global.regulatedStates.indexOf(state) > -1;
            // Get licenses from global list or from appointments page list
            const licenses = practitioner.attributes ? practitioner.attributes.licenses : practitioner.data.info.licenses;
            // If the user's state is regulated, filter dr list for drs with licenses in that state
            return userRegulatedState
              ? licenses.filter(license => license.state === state).length
              : true
          })
        },
        getAppointments(cb) {
            axios.get(`${this.apiUrl}/appointments?include=patient.user`)
                .then(response => {
                    this.global.appointments = combineAppointmentData(response.data).reverse();
                    this.global.loadingAppointments = true;
                    Vue.nextTick(() => {
                        this.global.loadingAppointments = false
                        if (cb) cb();
                    });
                }).catch(error => console.log(error.response));

            axios.get(`${this.apiUrl}/appointments?filter=upcoming&include=patient.user`)
                .then((response) => this.global.upcoming_appointments = response.data)
                .catch(error => console.log(error.response));

            axios.get(`${this.apiUrl}/appointments?filter=recent&include=patient.user`)
                .then((response) => this.global.recent_appointments = response.data)
                .catch(error => console.log(error.response));
        },
        getAvailability(id, cb) {
          axios.get(`/api/v1/practitioners/${id}?include=availability`).then(response => cb && typeof cb === 'function' ? cb(response) : false);
        },
        getPatients() {
            axios.get(`${this.apiUrl}/patients?include=user`).then(response => {
                const include = response.data.included;
                response.data.data.forEach((obj, i) => {
                    const includeData = include[i].attributes;
                      this.global.patients.push({
                        address_1: includeData.address_1,
                        address_2: includeData.address_2,
                        city: includeData.city,
                        date_of_birth: moment(obj.attributes.birthdate).format("MM/DD/YY"),
                        email: includeData.email,
                        has_a_card: includeData.has_a_card,
                        id: obj.id,
                        name: `${includeData.last_name}, ${includeData.first_name}`,
                        phone: includeData.phone,
                        search_name: `${includeData.first_name} ${includeData.last_name}`,
                        state: includeData.state,
                        user_id: obj.attributes.user_id,
                        zip: includeData.zip,
                    })
                });
                this.global.patients = sortByLastName(this.global.patients);
                if (this.global.practitioners.length && Laravel.user.user_type === 'practitioner') {
                  this.global.patients = this.filterPatients(this.global.patients, this.global.practitioners[0].info.licenses);
                }
                response.data.data.forEach(e => {
                    this.global.patientLookUp[e.id] = e
                });
                this.global.loadingPatients = false;
            });
        },
        getPractitioners() {
            if (Laravel.user.user_type !== 'practitioner') {
                axios.get(`${this.apiUrl}/practitioners?include=user`).then(response => {
                  if (Laravel.user.user_type === 'patient') {
                    this.global.practitioners = this.filterPractitioners(response.data.data, Laravel.user.state);
                  } else {
                    this.global.practitioners = response.data.data;
                  }
                  this.global.practitioners = this.global.practitioners.map(dr => {
                    return {
                      id: dr.id,
                      info: dr.attributes,
                      name: `Dr. ${dr.attributes.name}`,
                      user_id: dr.attributes.user_id
                    }
                  });
                  response.data.data.forEach(e => {
                      this.global.practitionerLookUp[e.id] = e
                  });
                  this.global.loadingPractitioners = false;
                })
            } else {
                axios.get(`${this.apiUrl}/practitioners?include=user`).then(response => {
                    this.global.practitioners = response.data.data.filter(dr => {
                        return dr.id === `${Laravel.user.practitionerId}`;
                    }).map(obj => {
                        return {
                          info: obj.attributes,
                          name: `Dr. ${obj.attributes.name}`,
                          id: obj.id,
                          user_id: obj.attributes.user_id };
                    });
                    response.data.data.forEach(e => {
                        this.global.practitionerLookUp[e.id] = e
                    });
                    if (this.global.patients.length && Laravel.user.user_type === 'practitioner') {
                      this.global.patients = this.filterPatients(this.global.patients, this.global.practitioners[0].info.licenses);
                    }
                    this.global.loadingPractitioners = false;
                    this.getSelfPractitionerInfo();
                })
            }
        },
        getLabData() {
            axios.get(`${this.apiUrl}/lab/orders?include=patient,user,invoice`)
                .then(response => {
                    this.global.labOrders = response.data.data.map((e, i) => {
                        e['included'] = response.data.included[i]
                        return e;
                    })
                    this.global.loadingLabOrders = false
                })

            axios.get(`${this.apiUrl}/lab/tests?include=sku`)
                .then(response => {
                    this.global.labTests = response.data.data.map((e, i) => {
                        e['included'] = response.data.included[i]
                        return e;
                    })
                    this.global.loadingLabTests = false
                })

            axios.get(`${this.apiUrl}/lab/tests/information`)
                .then(response => {
                    response.data.data.forEach(e => {
                        this.labTests[e.id] = e
                        this.labTests[e.id]['checked'] = false
                    })
                    this.global.loadingTestTypes = false
                })
        },
        getUser() {
            axios.get(`${this.apiUrl}/users/${Laravel.user.id}?include=patient,practitioner`)
                .then(response => {
                    let data = response.data.data;
                    if (response.data.included) {
                        data.included = response.data.included[0];
                    }
                    this.global.user = data;
                    this.global.loadingUser = false;
                })
                .catch(error => this.global.user = {});
        },
        getMessages() {
            let makeThreadId = (userOne, userTwo) => {
                return userOne > userTwo ? `${userTwo}-${userOne}` : `${userOne}-${userTwo}`
            };
            axios.get(`${this.apiUrl}/messages`)
                .then(response => {
                    let data = {};
                    response.data.data.forEach(e => {
                        data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] = data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] ?
                            data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] : [];
                        data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`].push(e);
                    });
                    if (data) {
                        Object.values(data).map(e => _.uniq(e.sort((a, b) => a.attributes.created_at - b.attributes.created_at)));
                        this.global.detailMessages = data;
                        this.global.messages = Object.values(data)
                            .map(e => e[e.length - 1])
                            .sort((a, b) => b.attributes.created_at.date - a.attributes.created_at.date);
                        this.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == Laravel.user.id)
                    }
                    this.global.loadingMessages = false
                })
        },
        getCreditCards() {
            axios.get(`${this.apiUrl}/users/${Laravel.user.id}/cards`)
            .then(response => {
                this.global.creditCards = response.data.cards
                this.global.loadingCreditCards = false;
            })
        },
        getConfirmedUsers() {
            this.global.confirmedDoctors = this.global.appointments
                .filter(e => e.attributes.status === 'complete')
                .map(e => this.global.practitioners.filter(ele => ele.id == e.attributes.practitioner_id)[0])
            this.global.confirmedPatients = this.global.appointments
                .filter(e => e.attributes.status === 'complete' || e.attributes.status === 'pending')
                .map(e => this.global.patients.filter(ele => ele.id == e.attributes.patient_id)[0])
            this.global.confirmedDoctors = _.uniq(this.global.confirmedDoctors).filter(e => _.identity(e))
            this.global.confirmedPatients = _.uniq(this.global.confirmedPatients)
        },
        getSelfPractitionerInfo() {
            let self = Object.values(this.global.practitionerLookUp).filter(e => e.attributes.user_id == Laravel.user.id)[0]
            this.global.selfPractitionerInfo = {
                id: self.id,
                name: `Dr. ${self.attributes.name}`,
                info: self.attributes,
                user_id: self.attributes.user_id
            }
        },
        getClientList() {
            axios.get(`${this.apiUrl}/users?type=patient`)
            .then(response => {
                this.clientList = response.data.data
                this.global.loadingClients = false
            })
        },
        setup() {
          this.getUser()
          this.getAppointments();
          this.getPractitioners();
          this.getMessages();
          this.getLabData();
          this.getConfirmedUsers();
          if (Laravel.user.user_type !== 'admin') this.getCreditCards();
          if (Laravel.user.user_type !== 'patient') this.getPatients();
          if (Laravel.user.user_type === 'admin') this.getClientList();
        },
        toDashboard() {
          if (this.signup.completedSignup) {
            window.location.href = '/dashboard';
          }
        },
        shouldTrack() {
          return env === 'production' || env === 'prod';
        }
    },
    mounted() {
        this.stripe = Stripe(Laravel.services.stripe.key);

        // This is helpful to have for development because you can test internal methods
        // that require application state
        if (App.Config.misc.environment === 'dev') {
          window.Root = window.Root || this;
        }

        // Initial GET requests
        if (Laravel.user.signedIn) this.setup();
    }
}).$mount('#app');
