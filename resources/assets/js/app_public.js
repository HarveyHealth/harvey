import './bootstrap';

// components
import LoginView from './components/Login.vue';
import RegisterView from './components/Register.vue';
import App from './components/App.vue';

const app = new Vue({
    components: {
        LoginView,
        RegisterView,
        App
    }
}).$mount('#app');