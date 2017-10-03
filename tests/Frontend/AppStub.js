import Vue from 'vue';

// Creates a mock app environment for a given component to resolve any
// references to $root. Add methods and properties as needed.
//
//  component = the component object being tested
//  componentName = string of the component name for the render function
//  setAppState = function that mutates the mock $root state prior to mount
export default function(component, componentName, setAppState) {
  // Set initial data
  let data = {
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

  return new Vue({
    data,
    components: { [`${componentName}`]: component },
    methods: {
      addTimezone() { return false; },
      filterPractitioners() { return false; },
      getAppointments() { return false; },
      shouldTrack() { return false; }
    },
    render(create) {
      return create('div', [create(componentName)])
    }
  })
}
