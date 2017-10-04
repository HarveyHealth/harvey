import Vue from 'vue';

import Flyout from '../../../resources/assets/js/commons/Flyout.vue';

const sandbox = sinon.sandbox.create();
const onCloseSpy = sandbox.spy();

const mockProps = {
  onClose: onCloseSpy,
  active: true,
}

describe('Flyout Component', () => {
  context('when close button clicked', () => {
    it('runs the onClick handler', () => {
      const Constructor = Vue.extend(Flyout);
      const wrapper = new Constructor({ propsData: mockProps }).$mount();

      const selection = wrapper.$el.querySelector('[data-test="close"]');
      const clickEvent = new window.Event('click');

      selection.dispatchEvent(clickEvent);

      expect(onCloseSpy).to.have.been.calledOnce;
    });
  });
});
