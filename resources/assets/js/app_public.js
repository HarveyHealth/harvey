import './bootstrap';

// components
import App from './components/App.vue';
import HomeView from './components/Home.vue';
import LoginView from './components/Login.vue';
import RegisterView from './components/Register.vue';

const app = new Vue({
    data: {
        guest: true
    },
    components: {
        App,
        HomeView,
        LoginView,
        RegisterView
    }
}).$mount('#appz');