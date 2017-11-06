import Vue from 'vue';
import LabOrders from '../../../resources/assets/js/pages/lab_orders/LabOrders.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
// import mockData from './mockData';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('LabOrders (General):', () => {
  context('When mounted', () => {
    const App = AppStub(LabOrders, 'LabOrders');
    it('it has a mounted hook', () => {
      expect(typeof LabOrders.mounted).to.equal('function');
    });
  });
});
