import Vue from 'vue';
import LogoIcon from '../../../resources/assets/js/v2/components/icons/LogoIcon.vue';
import AppStub from '../AppStub';
import LaravelStub from '../LaravelStub';
import mockData from '../mock_data';

window.Laravel = LaravelStub;
window.Vue = Vue;

describe('LogoIcon:', ()=> {
    context("When 'alwaysShowText' prop is passed", () => {
        const App = AppStub(LogoIcon, 'LogoIcon', { alwaysShowText: true });
        it("text <path> renders with 'always-text' class applied", () => {
            expect(App.contains('path.always-text')).to.equal(true);
        });
    });

    context("When 'hasDarkIcon' prop is passed", () => {
        const App = AppStub(LogoIcon, 'LogoIcon', { hasDarkIcon: true });
        it("icon <path> fill is '#5f7278'", () => {
            const fill = App.find('path').attributes.fill.value;
            expect(fill).to.equal('#5f7278');
        });
    });

    context("When 'hasDarkText' prop is passed", () => {
        const App = AppStub(LogoIcon, 'LogoIcon', { hasDarkText: true });
        it("text <path> fill is '#5f7278'", () => {
            const fill = App.find('.logo-text').attributes.fill.value;
            expect(fill).to.equal('#5f7278');
        });
    });

    context("When 'hasWhiteIcon' prop is passed", () => {
        const App = AppStub(LogoIcon, 'LogoIcon', { hasWhiteIcon: true });
        it("icon <path> fill is '#ffffff'", () => {
            const fill = App.find('path').attributes.fill.value;
            expect(fill).to.equal('#ffffff');
        });
    });

    context("When 'revealText' prop is passed", () => {
        const App = AppStub(LogoIcon, 'LogoIcon', { revealText: true });
        it("text <path> renders with 'reveal-text' class applied", () => {
            expect(App.contains('path.reveal-text')).to.equal(true);
        });
    });
});
