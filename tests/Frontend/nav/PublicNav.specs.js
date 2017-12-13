import Vue from 'vue';
import PublicNav from '../../../resources/assets/js/v2/components/nav/PublicNav.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
import mockData from '../mock_data';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('PublicNav:', ()=> {
    context("When 'givSpace' prop is passed", () => {
        const App = AppStub(PublicNav, 'PublicNav', { giveSpace: true });
        it('.nav-top-space has .is-active class applied to it', () => {
            expect(App.contains('.nav-top-space.is-active')).to.equal(true);
        });
    });

    context("When 'hasLinks' prop is passed", () => {
        const App = AppStub(PublicNav, 'PublicNav', { hasLinks: true });
        it('.nav-links renders', () => {
            expect(App.contains('.nav-links')).to.equal(true);
        });
    });

    context("When 'hasLogo' prop is passed", () => {
        const App = AppStub(PublicNav, 'PublicNav', { hasLogo: true });
        it('.nav-logo renders', () => {
            expect(App.contains('.nav-logo')).to.equal(true);
        });
    });

    context("When 'hasPhone' prop is passed", () => {
        const App = AppStub(PublicNav, 'PublicNav', { hasPhone: true });
        it('.nav-phone renders', () => {
            expect(App.contains('.nav-phone')).to.equal(true);
        });
    });

    context("When 'hasStart' prop is passed", () => {
        const App = AppStub(PublicNav, 'PublicNav', { hasStart: true });
        Laravel.user.user_type = 'admin';
        Laravel.user.signed_in = true;

        it('.nav-start renders', () => {
            expect(App.contains('.nav-start')).to.equal(true);
        });
        it('.nav-start button renders .top-nav-avatar if the user is signed in and either has an appointment or isn\'t a patient', () => {
            expect(App.contains('.top-nav-avatar')).to.equal(true);
        });
    });

    context("When 'isSticky' prop is passed", () => {
        const App = AppStub(PublicNav, 'PublicNav', { isSticky: true });
        it('.nav-is-sticky renders', () => {
            expect(App.contains('.nav-is-sticky')).to.equal(true);
        });
    });

    context("When .nav-hamburger is clicked", () =>{
        const App = AppStub(PublicNav, 'PublicNav');
        const _PublicNav = App.vm.$children[0];
        const beforeClickMenuState = _PublicNav.$data.isMenuActive;
        App.find('.nav-hamburger').click();

        it('toggles isMenuActive state', done => {
            Vue.nextTick(() => {
                expect(_PublicNav.$data.isMenuActive).to.not.equal(beforeClickMenuState);
                done();
            });
        });
    });
});
