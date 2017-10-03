import Vue from 'vue';
import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
import mockData from './mockData';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('Appointments:', () => {

  context('When mounted', () => {
    it('it has a mounted hook', () => {
      expect(typeof Appointments.mounted).to.equal('function');
    });
  })

  context('When global appointment data is available', () => {
    const App = AppStub(Appointments, 'Appointments').$mount();
    const _Appointments = App.$children[0];

    App.$data.global.appointments.push(mockData);
    App.$data.global.loadingAppointments = false;

    it('it will populate the appointments array', done => {
      Vue.nextTick(() => {
        expect(_Appointments.$data.appointments.length).to.equal(1);
        done();
      });
    });
    it('it will render appointments to the table', done => {
      Vue.nextTick(() => {
        console.log(App.$el);
        done();
      });
    });

  });
});
