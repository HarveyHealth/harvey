import './bootstrap';
import LoginView from './components/Login.vue';
import RegisterView from './components/Register.vue';

const app = new Vue({
    components: {
        LoginView,
        RegisterView
    },
    methods: {
        
    },
    mounted() {
        console.log('Public ready.')
    }
}).$mount('#app');