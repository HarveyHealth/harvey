import './bootstrap';

// components
import LoginView from './components/Login.vue';
import RegisterView from './components/Register.vue';
import App from './components/App.vue';

const app = new Vue({
    data: {
        guest: true,
        user: {}
    },
    components: {
        LoginView,
        RegisterView,
        App
    }
}).$mount('#app');