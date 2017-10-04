import Vue from 'vue';
import cheerio from 'cheerio';

// Creates a mock app environment for a given component to resolve any
// references to $root. Add methods and properties as needed.
//
//  component = the component object being tested
//  componentName = string of the component name for the render function
//  setAppState = function that mutates the mock $root state prior to mount
const AppStub = function(component, componentName, setAppState) {

  let stub = {};

  // Set initial data
  const data = {
    global: {
      appointments: [],
      currentPage: '',
      loadingAppointments: true,
      loadingPatients: true,
      loadingPractitioners: true,
      patients: [],
      practitioners: [],
    }
  };

  // Mutate $root data
  if (setAppState) setAppState(data);

  stub.vm = new Vue({
    data,
    components: { [`${componentName}`]: component },
    methods: {
      addTimezone() { return false; },
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
