import Vue from 'vue';
import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
import appointments from '../mock_data/global.appointments';
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

    App.vm.$data.global.appointments.push(appointments);
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

    App.vm.$data.global.appointments.push(appointments);
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

  context('When a user views a completed appointment', () => {
    let mockAppointmentData = JSON.parse(JSON.stringify(appointments));
    // The appointment must be in the past for the filters to apply properly
    mockAppointmentData.attributes.appointment_at.date = '2017-09-10 13:30:00.000000';
    mockAppointmentData.attributes.status = 'complete';
    mockAppointmentData.attributes.duration_in_minutes = '60'; // currently expects $150 charge
    Laravel.user.card_brand = 'Visa';
    Laravel.user.card_last4 = '5555';

    const App = AppStub(Appointments, 'Appointments');
    const _Appointments = App.vm.$children[0];

    App.vm.$data.global.appointments.push(mockAppointmentData);
    App.vm.$data.global.loadingAppointments = false;

    it('the flyout billing section will render', done => {
      Vue.nextTick(() => {
        // Table needs to be filtered to display a past, completed appointment
        App.find('.filters button + button').click();
        Vue.nextTick(() => {
          App.find('table .cell-wrap').click();
          Vue.nextTick(() => {
            expect(App.contains('[data-test="section_billing"]')).to.equal(true);
            done();
          });
        });
      });
    });

    it('the charged amount will correspond to the duration of the appointment', done => {
      expect(App.$('[data-test="appointment_amount_charged"]').text()).to.equal('Charged: $150');
      done();
    });
  });
});
