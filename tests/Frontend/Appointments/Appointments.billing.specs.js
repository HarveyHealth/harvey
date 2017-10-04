import Vue from 'vue';
import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
import mockData from './mockData';
import axios from 'axios';

window.axios = axios;
window.Laravel = LaravelStub;
window.Vue = Vue;

const sandbox = sinon.sandbox.create();
const onCloseSpy = sandbox.spy();

describe('Appointments (Billing):', () => {
  context('When an admin changes an appointment status to "complete"', () => {
    const App = AppStub(Appointments, 'Appointments');
    const _Appointments = App.vm.$children[0];

    App.vm.$data.global.appointments.push(mockData);
    App.vm.$data.global.loadingAppointments = false;

    it('the duration dropdown will render', done => {
      Vue.nextTick(() => {
        App.find('table .cell-wrap').click();
        _Appointments.setStatus({ value: 'Complete', data: 'complete' });
        Vue.nextTick(() => {
          expect(App.contains('[data-test="duration"]')).to.equal(true);
          done();
        });
      });
    });

    it('the update button will be disabled if duration is not set', done => {
      Vue.nextTick(() => {
        expect(App.$('[data-test="button_update"]').attr('disabled')).to.equal('disabled');
        done();
      });
    });
  });

  context('When a patient views a pending appointment', () => {
    Laravel.user.user_type = 'patient';

    const App = AppStub(Appointments, 'Appointments');
    const _Appointments = App.vm.$children[0];

    App.vm.$data.global.appointments.push(mockData);
    App.vm.$data.global.loadingAppointments = false;

    it('the appointment status will not be editable', done => {
      Vue.nextTick(() => {
        App.find('table .cell-wrap').click();
        Vue.nextTick(() => {
          expect(_Appointments.editableStatus).to.equal(false);
          done();
        });
      });
    });
  });
});
