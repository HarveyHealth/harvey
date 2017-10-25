import Vue from 'vue';
import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
import mockData from './mockData';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('Appointments (General):', () => {

  context('When mounted', () => {
    it('it has a mounted hook', () => {
      expect(typeof Appointments.mounted).to.equal('function');
    });
  });

  context('When no global appointments exist', () => {
    const App = AppStub(Appointments, 'Appointments');
    App.vm.$data.global.loadingAppointments = false;

    it('it will not render appointment rows to the table', done => {
      Vue.nextTick(() => {
        expect(App.contains('table .cell-wrap')).to.not.equal(true);
        done();
      });
    });
  });

  context('When global appointment data is available', () => {
    const App = AppStub(Appointments, 'Appointments');
    App.vm.$data.global.appointments.push(mockData);
    App.vm.$data.global.loadingAppointments = false;

    it('it will populate the appointments array', done => {
      Vue.nextTick(() => {
        expect(App.vm.$children[0].$data.appointments.length).to.equal(1);
        done();
      });
    });
    it('it will render appointment rows to the table', done => {
      Vue.nextTick(() => {
        expect(App.contains('table .cell-wrap')).to.equal(true);
        done();
      });
    });
  });
});
