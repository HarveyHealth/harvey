import Vue from 'vue';
import LabOrders from '../../../resources/assets/js/pages/lab_orders/LabOrders.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
import mockData from '../mock_data';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('LabOrders (Billing):', () => {
  // Using 'let' so we can reset the instance for patient user
  // let App = AppStub(LabOrders, 'LabOrders');
  // let _LabOrders = App.vm.$children[0];
  // let _Details = _LabOrders.$children[1];
  // _LabOrders.setupLabData();
  //
  // context('When user is an admin', () => {
  //   it('admins are able to create new lab order recommendations', done => {
  //     App.vm.$data.permissions = 'admin';
  //     Vue.nextTick(() => {
  //       expect(App.contains('[data-test="addLabOrder"]')).to.equal(true);
  //       done();
  //     });
  //   });

    // it('lab order flyout displays credit card error message when no card is on file', done => {
    //   App.find('.cell-wrap').click();
    //   Vue.nextTick(() => {
    //     _LabOrders.$data.loading = false;
    //     Vue.nextTick(() => {
    //       expect(App.contains('[data-test="lab-order-credit-card"] .error-text')).to.equal(true);
    //       done();
    //     });
    //   });
    // });

  //   it('lab order flyout displays credit card info when card is on file', done => {
  //     App.find('.cell-wrap').click();
  //     Vue.nextTick(() => {
  //       _LabOrders.$data.patientCard = mockData.patientCreditCard;
  //       _LabOrders.$data.loading = false;
  //       Vue.nextTick(() => {
  //         expect(App.contains('[data-test="lab-order-credit-card"] label.input__item')).to.equal(true);
  //         done();
  //       });
  //     });
  //   });
  // });

  // context('When user is a patient', () => {
  //   App = AppStub(LabOrders, 'LabOrders');
  //   _LabOrders = App.vm.$children[0];
  //   _Details = _LabOrders.$children[1];
  //   _LabOrders.setupLabData();

    // it('the flyout renders a list of recommended lab test checkbox inputs', done => {
    //   App.vm.$data.permissions = 'patient';
    //   App.find('.cell-wrap').click();
    //   Vue.nextTick(() => {
    //     expect(App.contains('[data-test="lab-test-recommendations"]')).to.equal(true);
    //     done();
    //   });
    // });

    // it('pricing info updates appropriately when a lab test is checked', done => {
    //   // First test selected is $299; processing fee is hardcoded
    //   const desiredSubTotal = 299 + _Details.$data.processingFee;
    //   App.find('[data-test="lab-test-recommendations"] .form-radio').click();
    //   Vue.nextTick(() => {
    //     expect(_Details.$data.subtotalAmount).to.equal(desiredSubTotal);
    //     done();
    //   });
    // });

    // it('the confirmation flyout displays the correct pricing information', done => {
    //   const itemPrice = 299;
    //   const processingFee = _Details.$data.processingFee;
    //   const subtotal = itemPrice + processingFee;
    //   const discounted = (subtotal / 2).toFixed(2);
    //
    //   const desiredItem = 'Micronutrients';
    //   const desiredItemPrice = `$${itemPrice}.00`;
    //   const desiredProcessingFee = `$${processingFee}.00`;
    //   const desiredDiscount = 'Discount (50%)';
    //   const desiredDiscountAmount = `- $${discounted}`;
    //   const desiredSubtotal = `$${itemPrice + processingFee}.00`;
    //   const desiredTotal = `$${(subtotal - discounted).toFixed(2)}`;
    //
    //   // Trigger the confirmation step
    //   App.find('.flyout .button-wrapper button').click();
    //
    //   Vue.nextTick(() => {
    //     // Add discount
    //     _Details.$data.discountCode = mockData.discountData.data.data.attributes.code;
    //     _Details.processDiscount(mockData.discountData);
    //
    //     Vue.nextTick(() => {
    //       expect(App.find('.flyout .left-column .sub-items').innerText).to.equal(desiredItem);
    //       expect(App.find('.flyout .right-column .sub-items').innerText).to.equal(desiredItemPrice);
    //       expect(App.find('.flyout .right-column .sub-items.processing').innerText).to.equal(desiredProcessingFee);
    //       expect(App.find('.flyout .right-column .sub-items.subtotal').innerText).to.equal(desiredSubtotal);
    //       expect(App.find('.flyout .left-column .sub-items.discount em').innerText).to.equal(desiredDiscount);
    //       expect(App.find('.flyout .right-column .sub-items.discount em').innerText).to.equal(desiredDiscountAmount);
    //       expect(App.find('.flyout .right-column .sub-items.total').innerText).to.equal(desiredTotal);
    //       done();
    //     });
    //   });
    // });
  // });
});
