import './bootstrap';

import VueRouter from 'vue-router';
Vue.use(VueRouter);
import router from './routes';

// filters
import filter_datetime from './filters/datetime';
Vue.filter('datetime', filter_datetime);

// components
import App from './components/App.vue';

const app = new Vue({
    router,
    components: {
        App
    }
}).$mount('#app');