import './bootstrap';
import router from './routes';

// FILTERS
import filter_datetime from './utils/filters/datetime';

// DIRECTIVES
import phonemask from './utils/directives/phonemask';
import VeeValidate from 'vee-validate';

// MIXINS
import TopNav from './utils/mixins/TopNav';

// COMPONENETS
import Alert from './commons/Alert.vue';
import Schedule from './pages/schedule/Schedule.vue';
import Dashboard from './pages/dashboard/Dashboard.vue';
import Usernav from './commons/UserNav.vue';

// HELPERS
import combineAppointmentData from './utils/methods/combineAppointmentData';
import moment from 'moment-timezone';
import sortByLastName from './utils/methods/sortByLastName';

Vue.filter('datetime', filter_datetime);
Vue.directive('phonemask', phonemask);
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

const app = new Vue({
    router,
    mixins: [TopNav],
    components: {
        Alert,
        Dashboard,
        Usernav,
    },
    data: {
        apiUrl: '/api/v1',
        appointmentData: null,
        clientList: [],
        environment: env,
        flyoutActive: false,
        guest: false,
        global: {
            appointments: [],
            confirmedDoctors: [],
            confirmedPatients: [],
            currentPage: '',
            detailMessages: {},
            loadingAppointments: true,
            loadingClients: true,
            loadingPatients: true,
            loadingPractitioners: true,
            practitionerProfileLoading: true,
            loadingLabOrders: true,
            loadingLabTests: true,
            loadingTestTypes: true,
            loadingUser: true,
            menuOpen: false,
            messages: [],
            patients: [],
            practitioners: [],
            recent_appointments: [],
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
            user: {}
        },
        signup: {
          availability: [],
          availableTimes: [],
          code: '',
          completedSignup: false,
          codeConfirmed: false,
          cost: '',
          data: {
            appointment_at: null,
            reason_for_visit: 'First appointment',
            practitioner_id: null,
          },
          phone: '',
          phonePending: false,
          phoneConfirmed: false,
          practitionerName: '',
          practitionerState: '',
          selectedDate: null,
          selectedDay: null,
          selectedPractitioner: null,
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
                    this.global.patients.push({
                        id: obj.id,
                        name: `${include[i].attributes.last_name}, ${include[i].attributes.first_name}`,
                        email: include[i].attributes.email,
                        phone: include[i].attributes.phone,
                        user_id: obj.attributes.user_id,
                        search_name: `${include[i].attributes.first_name} ${include[i].attributes.last_name}`,
                        date_of_birth: moment(obj.attributes.birthdate).format("MM/DD/YY")
                    })
                });
                this.global.patients = sortByLastName(this.global.patients);
                response.data.data.forEach(e => {
                    this.global.patientLookUp[e.id] = e
                });
                this.global.loadingPatients = false;
            });
        },
        getPractitioners() {
            if (Laravel.user.user_type !== 'practitioner') {
                axios.get(`${this.apiUrl}/practitioners?include=user`).then(response => {
                    this.global.practitioners = response.data.data.map(dr => {
                        return {
                          info: dr.attributes,
                          name: `Dr. ${dr.attributes.name}`,
                          id: dr.id,
                          user_id: dr.attributes.user_id }
                    });
                    response.data.data.forEach(e => {
                        this.global.practitionerLookUp[e.id] = e
                    });
                    this.global.loadingPractitioners = false;
                })
            } else {
                axios.get(`${this.apiUrl}/practitioners?include=user`).then(response => {
                    this.global.practitioners = response.data.data.filter(dr => {
                        return dr.attributes.name === Laravel.user.fullName;
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
                    this.global.loadingPractitioners = false;
                })
            }
        },
        getLabData() {
            axios.get(`${this.apiUrl}/lab/orders?include=patient,user`)
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
            axios.get(`${this.apiUrl}/messages`)
                .then(response => {
                    let data = {};
                    response.data.data.forEach(e => {
                        data[e.attributes.subject] = data[e.attributes.subject] ?
                            data[e.attributes.subject] : [];
                        data[e.attributes.subject].push(e);
                    });
                    if (data) {
                        Object.values(data).map(e => _.uniq(e.sort((a, b) => a.attributes.created_at - b.attributes.created_at)));
                        this.global.detailMessages = data;
                        this.global.messages = Object.values(data)
                            .map(e => e[e.length - 1])
                            .sort((a, b) => b.attributes.created_at.date - a.attributes.created_at.date)
                            .sort((a, b) => ((a.attributes.read_at == null || b.attributes.read_at == null) &&
                                (Laravel.user.id == a.attributes.recipient_user_id || Laravel.user.id == b.attributes.recipient_user_id) ? 1 : -1));
                        this.global.unreadMessages = response.data.data.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == Laravel.user.id)
                    }
                })
        },
        getConfirmedUsers() {
            this.global.confirmedDoctors = this.global.appointments
                .filter(e => e.attributes.status === 'complete')
                .map(e => this.global.practitioners.filter(ele => ele.id == e.attributes.practitioner_id)[0])
            this.global.confirmedPatients = this.global.appointments
                .filter(e => e.attributes.status === 'complete')
                .map(e => this.global.patients.filter(ele => ele.id == e.attributes.patient_id)[0])
            this.global.confirmedDoctors = _.uniq(this.global.confirmedDoctors)
            this.global.confirmedPatients = _.uniq(this.global.confirmedPatients)
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
          if (Laravel.user.user_type !== 'patient') this.getPatients();
          if (Laravel.user.user_type === 'admin') this.getClientList();
        },
        toDashboard() {
          if (this.signup.completedSignup) {
            window.location.href = '/dashboard';
          }
        }
    },
    mounted() {
        Stripe.setPublishableKey(Laravel.services.stripe.key);
        window.debug = () => console.log(this.$data);

        // Initial GET requests
        if (Laravel.user.signedIn) this.setup();
    }
}).$mount('#app');
