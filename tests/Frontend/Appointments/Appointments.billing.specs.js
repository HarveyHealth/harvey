import Vue from 'vue';
// import App from '../../../resources/assets/js/app';
import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';
// import LoadingGraphic from '../../../resources/assets/js/commons/LoadingGraphic.vue';

window.Laravel = {
  user: {
    has_a_card: false
  }
};

describe('test', () => {
  it('test', () => {
    const app = new Vue({
      components: {
        Appointments
      },
      data: {
        global: {
          appointments: [],
          currentPage: '',
          loadingAppointments: false,
          loadingPatients: false,
          loadingPractitioners: false,
          patients: [],
          practitioners: [],
        }
      },
      methods: {
        addTimezone() { return false; },
        filterPractitioners() { return false; },
        getAppointments() { return false; },
        shouldTrack() { return false; }
      },
      render(create) {
        return create('div', [create('Appointments')])
      }
    }).$mount()
    console.log(app.$el);
  })
})
