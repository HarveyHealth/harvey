import Vue from 'vue';
import Records from '../../../../resources/assets/js/pages/records/Records.vue';
import AppStub from '../../AppStub';
import LaravelStub from '../../LaravelStub';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('Records (General):', () => {
    const App = AppStub(Records, 'Records');
    const _Records= App.vm.$children[0];
    
    context('When mounted', () => {
        it('it has a mounted hook (initial test)', () => {
            expect(typeof Records.mounted).to.equal('function');
        });
    });

});