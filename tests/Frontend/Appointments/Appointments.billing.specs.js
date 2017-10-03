import Vue from 'vue';
// import App from '../../../resources/assets/js/app';
import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';
// import LoadingGraphic from '../../../resources/assets/js/commons/LoadingGraphic.vue';

window.Laravel = {
  user: {
    has_a_card: false
  }
};

const app = new Vue({
  components: { Appointments },
  data: {
    global: {
      patients: []
    }
  },
  render(create) {
    return create('Appointments')
  }
});

describe('test', () => {
  it('test', () => {
    // app.$mount()
    console.log(app.$children)
  })
})
