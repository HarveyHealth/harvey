import Vue from 'vue';
import DetailMessage from '../../../../resources/assets/js/pages/messages/DetailMessage.vue';
import AppStub from '../../AppStub';
import LaravelStub from '../../LaravelStub';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('Detail Messages (General):', () => {
    const App = AppStub(DetailMessage, 'DetailMessage');
    const _DetailMessage = App.vm.$children[0];
    
    context('When mounted', () => {
        it('it has a mounted hook (initial test)', () => {
            expect(typeof DetailMessage.mounted).to.equal('function');
        });
    });

});