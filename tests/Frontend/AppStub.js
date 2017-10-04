import Vue from 'vue';
import cheerio from 'cheerio';
import store from '../../resources/assets/js/store';
import laravel from './LaravelStub';

// methods
import filterPractitioners from '../../resources/assets/js/utils/methods/filterPractitioners';

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
  const data = store(laravel);

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

  return stub;
}

export default AppStub;
