import './bootstrap';
import router from './routes';

// DIRECTIVES
import VeeValidate from 'vee-validate';
import VueRouter from 'vue-router';
import VueQuillEditor from 'vue-quill-editor';

import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';

Vue.use(VeeValidate);
Vue.use(VueRouter);
Vue.use(VueQuillEditor);

// COMPONENETS
import Dashboard from './v2/components/pages/dashboard/Dashboard.vue';
import { GridStyles } from 'layout';
import Usernav from './commons/UserNav.vue';

// METHODS
import combineAppointmentData from './utils/methods/combineAppointmentData';
import filterPractitioners from './utils/methods/filterPractitioners';
import moment from 'moment-timezone';
import sortByLastName from './utils/methods/sortByLastName';
import _ from 'lodash';

const env = require('get-env')();
window.Card = require('card');

// Centralized event handler to easily share among components
const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

eventHub.$on('animate', (classes, classname, state, delay) => {
    if (delay) {
        setTimeout(() => classes[classname] = state, delay);
    } else {
        classes[classname] = state;
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
App.Util = Util;
App.Filters = Filters;
App.Http = Http;
App.Logic = Logic;
App.Router = router;

// Register global filters
Vue.filter('formatPhone', Filters.formatPhone);
Vue.filter('fullName', App.Util.misc.fullName);
Vue.filter('fullDate', Filters.fullDate);
Vue.filter('timeDisplay', Filters.timeDisplay);
Vue.filter('weekDay', Filters.weekDay);
Vue.filter('ucfirst', function (value) {
  return value.substr(0,1).toUpperCase() + value.substr(1);
});

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
  return App.Util.data.propDeep(path.split('.'), State, ifUndefined);
};

// State() is internally read only and setState() is globally write-only.
//    App.setState('practitioners.data.all', 'practitioners');
//    State.practitioners.data.all yields 'practitioners'
App.setState = (state, value) => {
  const set = (s, v) => {
    const path = s.split('.');
    const prop = path.pop();
    return App.Util.data.propDeep(path, State)[prop] = v;
  };

  switch(typeof state) {
      case 'string':
        set(state, value);
        break;
      case 'object':
        for (var key in state) {
          set(key, state[key]);
        }
        break;
  }

};

Vue.prototype.setState = App.setState;

// STORE
// The data object for the root Vue instance. We're abstracting this to its own file
// so it can be imported into our app stub for unit testing
import store from './store';
const Store = store(Laravel, State);

const app = new Vue({
    router,
    components: {
        Dashboard,
        GridStyles,
        Usernav
    },
    // Adding State to the root data object makes it globally reactive.
    // We do not attach this to window.App for HIPPA compliance. Use
    // App.setState to mutate this object.
    data: Store,

    computed: {
        userIsPatient() {
            return 'patient' === Laravel.user.user_type;
        },
        userIsNotPatient() {
            return !this.userIsPatient;
        },
        userIsPractitioner() {
            return 'practitioner' === Laravel.user.user_type;
        },
        userIsNotPractitioner() {
            return !this.userIsPractitioner;
        },
        userIsAdmin() {
            return 'admin' === Laravel.user.user_type;
        },
        userIsNotAdmin() {
            return !this.userIsAdmin;
        },
        userIsAdminOrPractitioner() {
            return this.userIsAdmin || this.userIsPractitioner;
        },
        userIsNotAdminOrPractitioner() {
            return !this.userIsAdminOrPractitioner;
        },
        userHasACard() {
            return Laravel.user.has_a_card;
        },
        userHasNotACard() {
            return !this.userHasACard;
        },
        userCardBrand() {
            return Laravel.user.card_brand;
        },
        userCardLast4() {
            return Laravel.user.card_last4;
        },
        userType() {
            return Laravel.user.userType;
        },
        isMobileMenuOpen() {
            return this.State.misc.isMobileMenuOpen ? 'menu-is-open' : '';
        },
        isSignupBookingAllowed() {
            return this.signup.billingConfirmed &&
            this.signup.phoneConfirmed &&
            this.signup.data.appointment_at &&
            this.signup.data.practitioner_id;
      }
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
          });
        },

        // Cannot just bind this to filterPractitioners. You must wrap it in a function
        // for 'this' to refer to the Vue instance
        filterPractitioners(practitioners, state) {
          return filterPractitioners.call(this, practitioners, state);
        },

        getAppointments(cb) {
            App.setState('appointments.isLoading.upcoming', true);
            axios.get(`${this.apiUrl}/appointments?include=patient.user,invoice`)
                .then(response => {
                    this.global.appointments = combineAppointmentData(response.data).reverse();
                    this.global.loadingAppointments = true;
                    Vue.nextTick(() => {
                        this.global.loadingAppointments = false;
                        if (cb) cb();
                    });
                })
                .catch(error => {
                  if (error.response) console.warn(error.response);
                });

            axios.get(`${this.apiUrl}/appointments?filter=upcoming&include=patient.user,invoice`)
                .then((response) => {
                  this.global.upcoming_appointments = response.data;
                  // to update v2 Dashboard
                  App.Http.appointments.getUpcomingResponse(response);
                })
                .catch(error => {
                  if (error.response) console.warn(error.response);
                });
        },
        getAvailability(id, cb) {
          axios.get(`/api/v1/practitioners/${id}?include=availability`).then(response => cb && typeof cb === 'function' ? cb(response) : false);
        },
        requestPatients(term = '', cb = null) {
            let params = {
                include: 'user'
            };

            if (term != '') {
                params.term = term;
            }

            axios.get(`${this.apiUrl}/patients`, {params: params}).then(response => {
                let patients = [];
                let patientLookUp = [];
                const include = response.data.included;

                response.data.data.forEach((obj, i) => {
                    const includeData = include[i].attributes;
                    patients.push({
                        address_1: includeData.address_1,
                        address_2: includeData.address_2,
                        city: includeData.city,
                        date_of_birth: obj.attributes && obj.attributes.birthdate && obj.attributes.birthdate.date ? moment(obj.attributes.birthdate.date).format('MM/DD/YY') : 'N/A',
                        email: includeData.email,
                        has_a_card: includeData.has_a_card,
                        id: obj.id,
                        name: `${includeData.last_name}, ${includeData.first_name}`,
                        phone: includeData.phone,
                        search_name: `${includeData.first_name} ${includeData.last_name} (#${obj.attributes.user_id})`,
                        state: includeData.state,
                        user_id: obj.attributes.user_id,
                        zip: includeData.zip,
                        image: includeData.image_url,
                        created_at: moment(includeData.created_at.date).format("MM/DD/YY")
                    });
                });

                patients = sortByLastName(patients);

                // if logged in user is practitioner, filter by states where the user can practice
                if (this.global.practitioners.length && Laravel.user.user_type === 'practitioner') {
                  patients = this.filterPatients(patients, this.global.practitioners[0].info.licenses);
                }

                // build an array with patient data to look up by ID
                response.data.data.forEach(e => {
                    patientLookUp[e.id] = e;
                });
                this.global.patients = _.uniqBy(this.global.patients, 'id');
                this.global.loadingPatients = false;

                if (cb){
                    cb(patients, patientLookUp);
                }
            });
        },
        getPatients() {
            this.requestPatients('',(patients, patientLookUp)=>{
                this.global.patients = patients;
                this.global.patientLookUp = patientLookUp;
            });
        },
        getPractitioners() {
            if (Laravel.user.user_type !== 'practitioner') {
                axios.get(`${this.apiUrl}/practitioners?include=user`).then(response => {
                let cache = {};
                if (response.data.included) {
                    response.data.included.forEach(e => {
                        cache[e.id] = e;
                    });
                }
                  if (Laravel.user.user_type === 'patient') {
                    this.global.practitioners = this.filterPractitioners(response.data.data, Laravel.user.state);
                  } else {
                    this.global.practitioners = response.data.data.map((e) => Object.assign({}, e, { included: cache[e.attributes.user_id] || null }));
                  }
                  this.global.practitioners = this.global.practitioners.map(dr => {
                    return {
                      id: dr.id,
                      info: dr.attributes,
                      name: `Dr. ${dr.attributes.name}`,
                      search_name: `Dr. ${dr.attributes.name}`,
                      email: dr.included && dr.included.attributes ? dr.included.attributes.email : null,
                      user_id: dr.attributes.user_id
                    };
                  });
                  response.data.data.forEach(e => {
                      this.global.practitionerLookUp[e.id] = e;
                  });
                  this.global.practitioners = _.uniqBy(this.global.practitioners, 'id');
                  this.global.loadingPractitioners = false;
                });
            } else {
                axios.get(`${this.apiUrl}/practitioners?include=user`).then(response => {
                    let cache = {};
                    if (response.data.included) {
                        response.data.included.forEach(e => {
                            cache[e.id] = e;
                        });
                    }
                    this.global.practitioners = response.data.data.map((e) => Object.assign({}, e, { included: cache[e.attributes.user_id] || null })).filter(dr => {
                        return dr.id === `${Laravel.user.practitionerId}`;
                    }).map(obj => {
                        return {
                          info: obj.attributes,
                          name: `Dr. ${obj.attributes.name}`,
                          search_name: `Dr. ${obj.attributes.name}`,
                          id: obj.id,
                          email: obj.included && obj.included.attributes ? obj.included.attributes.email : null,
                          user_id: obj.attributes.user_id };
                    });
                    response.data.data.forEach(e => {
                        this.global.practitionerLookUp[e.id] = e;
                    });
                    if (this.global.patients.length && Laravel.user.user_type === 'practitioner') {
                      this.global.patients = this.filterPatients(this.global.patients, this.global.practitioners[0].info.licenses);
                    }
                    this.global.practitioners = _.uniqBy(this.global.practitioners, 'id');
                    this.global.patients = _.uniqBy(this.global.patients, 'id');
                    this.global.loadingPractitioners = false;
                    this.getSelfPractitionerInfo();
                });
            }
        },
        getLabData() {
            axios.get(`${this.apiUrl}/lab/orders?include=patient,user,invoice`)
                .then(response => {
                    if (response.data.included) {
                        let user = response.data.included.filter(e => e.type === 'user');
                        let patient = response.data.included.filter(e => e.type === 'patient');
                        let invoices = response.data.included.filter(e => e.type === 'invoice');
                        let obj = {};
                        if (invoices.length) {
                            invoices.forEach(e => {
                                obj[e.id] = e;
                            });
                        }
                        this.global.labOrders = response.data.data.map((e, i) => {
                            e.user = user[i];
                            e.patient = patient[i];
                            if (e.relationships.invoice) {
                                e.invoice = obj[e.relationships.invoice.data.id];
                            }
                            return e;
                        });
                    }
                    this.global.loadingLabOrders = false;
                });

            axios.get(`${this.apiUrl}/lab/tests?include=sku`)
                .then(response => {
                    let sku_ids = {};
                    if (!response.data.included) {
                        this.global.labTests = response.data.data;
                        this.global.loadingLabTests = false;
                        return;
                    }
                    response.data.included.forEach(e => {
                        sku_ids[e.id] = e;
                    });
                    this.global.labTests = response.data.data.map((e) => {
                        e.included = sku_ids[e.relationships.sku.data.id];
                        return e;
                    });
                    this.global.loadingLabTests = false;
                });

            axios.get(`${this.apiUrl}/lab/tests/information`)
                .then(response => {
                    response.data.data.forEach(e => {
                        this.labTests[e.attributes.sku_id] = e;
                        if (e.attributes && e.attributes.lab_name && this.labTypes[e.attributes.lab_name] === undefined) { 
                            this.labTypes[e.attributes.lab_name] = e.attributes.lab_name; 
                        }
                        this.labTests[e.attributes.sku_id]['checked'] = false;
                    });
                    this.global.loadingTestTypes = false;
                });
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
                .catch(() => this.global.user = {});
        },
        getMessages() {
            let makeThreadId = (userOne, userTwo) => {
                return userOne > userTwo ? `${userTwo}-${userOne}` : `${userOne}-${userTwo}`;
            };
            axios.get(`${this.apiUrl}/messages`)
                .then(response => {
                    let data = {};
                    let messageData = response.data.data;
                    messageData.forEach(e => {
                        data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] = data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] ?
                            data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`] : [];
                        data[`${makeThreadId(e.attributes.sender_user_id, e.attributes.recipient_user_id)}-${e.attributes.subject}`].push(e);
                    });
                    if (data) {
                        Object.values(data).map(e => _.uniq(e.sort((a, b) => a.id - b.id)));
                        this.global.detailMessages = data;
                        this.global.messages = Object.values(data)
                            .map(e => e[e.length - 1])
                            .sort((a, b) => b.id - a.id);
                        this.global.unreadMessages = messageData.filter(e => e.attributes.read_at == null && e.attributes.recipient_user_id == Laravel.user.id);
                    }
                    this.global.loadingMessages = false;
                });
        },
        getCreditCards() {
            axios.get(`${this.apiUrl}/users/${Laravel.user.id}/cards`)
            .then(response => {
                this.global.creditCards = response.data.data;
            })
            .then(() => {
                this.global.loadingCreditCards = false;
            });
        },
        getConfirmedUsers() {
            let doctors = this.global.practitioners;
            let patients = this.global.patients;
            this.global.confirmedDoctors = doctors;
            this.global.confirmedPatients = patients;
            this.global.practitioners = _.uniqBy(doctors, 'id').filter(e => e !== undefined);
            this.global.patients = _.uniqBy(patients, 'id').filter(e => e !== undefined);
            this.global.confirmedDoctors = _.uniqBy(this.global.confirmedDoctors, 'id').filter(e => e !== undefined);
            this.global.confirmedPatients = _.uniqBy(this.global.confirmedPatients, 'id').filter(e => e !== undefined);
            this.global.loadingConfirmedUsers = false;
        },
        requestConfirmedUsers(term='', cb=null) {
            this.getConfirmedUsers();
            let regex = new RegExp(term, 'ig');
            if (cb) {
                if (this.permissions === 'admin') {
                    let all = this.global.confirmedDoctors.concat(this.global.confirmedPatients).filter(e => {
                        for (let i in e) {
                            if (regex.test(e[i])) {
                                return true;
                            }
                        }
                        return false;
                    });
                    cb(all, all
                    .reduce((acc, item)  => {
                        acc[item.id] = item;
                        return acc;
                    }, {}));
                } else if (this.permissions === 'practitioner') {
                    let patients = this.global.confirmedPatients.filter(e => {
                        for (let i in e) {
                            if (regex.test(e[i])) {
                                return true;
                            }
                        }
                        return false;
                    });
                    cb(patients, patients.reduce((acc, item)  => {
                        acc[item.id] = item;
                        return acc;
                    }, {}));
                } else {
                    let doctors = this.global.confirmedDoctors.filter(e => {
                        for (let i in e) {
                            if (regex.test(e[i])) {
                                return true;
                            }
                        }
                        return false;
                    });
                    cb(doctors, doctors.reduce((acc, item)  => {
                        acc[item.id] = item;
                        return acc;
                    }, {}));
                }
            }
        },
        getSelfPractitionerInfo() {
            let self = Object.values(this.global.practitionerLookUp).filter(e => e.attributes.user_id == Laravel.user.id)[0];
            this.global.selfPractitionerInfo = {
                id: self.id,
                name: `Dr. ${self.attributes.name}`,
                info: self.attributes,
                user_id: self.attributes.user_id
            };
        },
        getClientList() {
            axios.get(`${this.apiUrl}/users?type=patient`)
            .then(response => {
                this.clientList = response.data;
                this.global.loadingClients = false;
            });
        },
        setup() {
            this.getUser();
            this.getAppointments();
            this.getPractitioners();
            this.getMessages();
            this.getLabData();
            if (Laravel.user.user_type !== 'admin') this.getCreditCards();
            if (Laravel.user.user_type !== 'patient') this.getPatients();
            if (Laravel.user.user_type === 'patient') {
                this.global.loadingPatients = false;
            }
            if (Laravel.user.user_type === 'admin') this.getClientList();
        },
        toDashboard() {
          if (this.signup.completedSignup) {
            window.location.href = '/dashboard';
          }
        }
    },
    mounted() {
        this.stripe = Stripe(Laravel.services.stripe.key);

        // This is helpful to have for development because you can test internal methods
        // that require application state
        if (App.Config.misc.environment === 'dev') {
          window.state = this.State;
        }

        // For conditions, we could either create an endpoint that will need to be hit
        // as soon as the page loads, or we expose a function on the window object that
        // will set the application state.
        window.setConditions = (data, index) => {
          this.State.conditions.all = data;
          this.State.conditions.selectedIndex = index;
        };

        // Initial GET requests
        if (Laravel.user.signedIn) {
            this.setup();
        }
    }
});

Vue.config.devtools = env !== 'production';

app.$mount('#app');
