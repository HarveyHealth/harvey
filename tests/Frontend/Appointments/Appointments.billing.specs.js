import Vue from 'vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';

import Appointments from '../../../resources/assets/js/pages/appointments/Appointments.vue';
import mockData from './mockData';

window.Laravel = LaravelStub;
window.Vue = Vue;

// describe('Appointments / Billing', () => {
//   context('When give it this data', () => {
//
//   });
// })
