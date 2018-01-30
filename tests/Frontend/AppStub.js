import Vue from 'vue';
import VueRouter from 'vue-router';
import cheerio from 'cheerio';
import store from '../../resources/assets/js/store';
import laravel from './LaravelStub';
import mockData from './mock_data';

Vue.use(VueRouter);

// methods
import filterPractitioners from '../../resources/assets/js/utils/methods/filterPractitioners';

// v2 architecture
import Config from '../../resources/assets/js/v2/config';
import Filters from '../../resources/assets/js/v2/filters';
import Http from '../../resources/assets/js/v2/http';
import Logic from '../../resources/assets/js/v2/logic';
import State from '../../resources/assets/js/v2/state';
import Util from '../../resources/assets/js/v2/util';

window.App = {};
App.Config = Config(laravel);
App.Util = Util;
App.Filters = Filters;
App.Http = Http;
App.Logic = Logic;

// Register global filters
Vue.filter('formatPhone', Filters.formatPhone);
Vue.filter('fullName', App.Util.misc.fullName);
Vue.filter('jsonParse', Filters.jsonParse);
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

// app_public.js attaches Laravel to the Vue prototype because it does
// not share v2 architecture
Vue.prototype.Laravel = laravel;


// Creates a mock app environment for a given component to resolve any
// references to $root. Add methods and properties as needed.
//
//  component = the component object being tested
//  componentName = string of the component name for the render function
//  setAppState = function that mutates the mock $root state prior to mount
const AppStub = function(component, componentName, props, setAppState) {

  let stub = {};

  // store is a function that takes in a Laravel object to set particular state.
  // this returns the same object used in the actual application.
  const data = store(laravel, State);
  App.State = data.State;
  Vue.prototype.State = App.State;

  data.labTests = mockData.labTests;
  data.global.labOrders.push(mockData.global.labOrders);
  data.global.labTests = mockData.global.labTests;
  data.global.patientLookUp = mockData.global.patientLookup;
  data.global.practitionerLookUp = mockData.global.practitionerLookup;

  data.global.loadingLabOrders = false;
  data.global.loadingLabTests = false;
  data.global.loadingTestTypes = false;
  data.global.loadingPatients = false;
  data.global.loadingPractitioners = false;
  data.global.loadingUser  = false;
  // data.global.creditCards = false;

  // Mutate $root data
  if (setAppState) setAppState(data, window);

  // Stub a Vue router instance
  let router = new VueRouter({
      routes: [],
      linkActiveClass: 'is-active'
  });

  stub.vm = new Vue({
    router,
    data,
    components: { [`${componentName}`]: component },
    methods: {
      addTimezone() { return false; },
      filterPractitioners: filterPractitioners.bind(this),
      getAppointments() { return false; },
      getLabData() { return false; },
      shouldTrack() { return false; }
    },
    computed: {
          userIsPatient() {
              return 'patient' === laravel.user.user_type;
          },
          userIsNotPatient() {
              return !this.userIsPatient;
          },
          userIsPractitioner() {
              return 'practitioner' === laravel.user.user_type;
          },
          userIsNotPractitioner() {
              return !this.userIsPractitioner;
          },
          userIsAdmin() {
              return 'admin' === laravel.user.user_type;
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
    },
    render(create) {
      return create('div', [
        create(componentName, { props: props || {} })
      ])
    }
  }).$mount();

  stub.$ = function(selector) {
    const dom = cheerio.load(stub.vm.$el.outerHTML);
    return dom(selector);
  }

  stub.contains = function(selector) {
    return stub.$(selector).html() === null ? false : true;
  }

  stub.find = selector => stub.vm.$el.querySelector(selector);

  return stub;
}

export default AppStub;
