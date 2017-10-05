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
  context("When a pending appointment's status changes to 'complete'", () => {
    const App = AppStub(Appointments, 'Appointments');
    const _Appointments = App.vm.$children[0];

    App.vm.$data.global.appointments.push(mockData);
    App.vm.$data.global.loadingAppointments = false;

    it('the duration dropdown renders', done => {
      Vue.nextTick(() => {
        // Click the first row
        App.find('table .cell-wrap').click();
        // setStatus is the callback that runs when status select box is changed
        _Appointments.setStatus({ value: 'Complete', data: 'complete' });

        Vue.nextTick(() => {
          // The duration dropdown should render at this point
          expect(App.contains('[data-test="duration"]')).to.equal(true);
          done();
        });
      });
    });

    it('the update button is disabled if duration is unset', done => {
      Vue.nextTick(() => {
        expect(App.$('[data-test="button_update"]').attr('disabled')).to.equal('disabled');
        done();
      });
    });

    it('the update button is enabled if duration is set', done => {
      _Appointments.setDuration({ data: 30, value: '30 minutes' });
      Vue.nextTick(() => {
        expect(App.$('[data-test="button_update"]').attr('disabled')).to.equal(undefined);
        done();
      });
    });

    it('the confirmation modal renders when Update button is clicked', done => {
      App.find('[data-test="button_update"]').click();
      Vue.nextTick(() => {
        expect(App.contains('[data-test="modal_confirmation"]')).to.equal(true);
        done();
      });
    });
  });

  context('When a patient views a pending appointment', () => {
    // Change mock Laravel object prior to mounting the mock App
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
