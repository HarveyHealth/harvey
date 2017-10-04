import Vue from 'vue';
import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';

describe('Appointments', () => {
  it('has a mounted hook', () => {
    expect(typeof Appointments.mounted).to.equal('function');
  });
});
