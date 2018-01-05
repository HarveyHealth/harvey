import Vue from 'vue';
import Messages from '../../../../resources/assets/js/pages/messages/Messages.vue';
import AppStub from '../../AppStub';
import LaravelStub from '../../LaravelStub';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('Messages (General):', () => {
    const App = AppStub(Messages, 'Messages');
    const _Messages = App.vm.$children[0];
    
    context('When mounted', () => {
        it('it has a mounted hook (initial test)', () => {
            expect(typeof Messages.mounted).to.equal('function');
        });
    });

});