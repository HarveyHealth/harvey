import './bootstrap';

// components
import HomeView from './components/Home.vue';
import LoginView from './components/Login.vue';
import RegisterView from './components/Register.vue';
import App from './components/App.vue';

const app = new Vue({
    data: {
        guest: true,
        user: {}
    },
    components: {
        HomeView,
        LoginView,
        RegisterView,
        App
    }
}).$mount('#app');