import Vue from 'vue';
import LabOrders from '../../../resources/assets/js/pages/lab_orders/LabOrders.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('LabOrders (General):', () => {
  const App = AppStub(LabOrders, 'LabOrders');
  const _LabOrders = App.vm.$children[0];

  context('When mounted', () => {
    it('it has a mounted hook (initial test)', () => {
      expect(typeof LabOrders.mounted).to.equal('function');
    });
  });

  context('When global labOrder data is available', () => {
    _LabOrders.setupLabData();

    it('the table has at least one row', done => {
      Vue.nextTick(() => {
        expect(App.contains('table .cell-wrap')).to.equal(true);
        done();
      });
    });
  });

});
