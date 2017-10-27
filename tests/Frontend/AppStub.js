import Vue from 'vue';
import cheerio from 'cheerio';
import store from '../../resources/assets/js/store';
import laravel from './LaravelStub';

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
}

// State() is internally read only and setState() is globally write-only.
//    App.setState('practitioners.data.all', 'practitioners');
//    State.practitioners.data.all yields 'practitioners'
App.setState = (state, value) => {
  const path = state.split('.');
  const prop = path.pop();
  return App.Util.data.propDeep(path, State)[prop] = value;
}

Vue.prototype.setState = App.setState;

// Creates a mock app environment for a given component to resolve any
// references to $root. Add methods and properties as needed.
//
//  component = the component object being tested
//  componentName = string of the component name for the render function
//  setAppState = function that mutates the mock $root state prior to mount
const AppStub = function(component, componentName, setAppState) {

  let stub = {};

  // store is a function that takes in a Laravel object to set particular state.
  // this returns the same object used in the actual application.
  const data = store(laravel, State);

  // Mutate $root data
  if (setAppState) setAppState(data);

  stub.vm = new Vue({
    data,
    components: { [`${componentName}`]: component },
    methods: {
      addTimezone() { return false; },
      filterPractitioners: filterPractitioners.bind(this),
      getAppointments() { return false; },
      shouldTrack() { return false; }
    },
    render(create) {
      return create('div', [create(componentName)])
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
