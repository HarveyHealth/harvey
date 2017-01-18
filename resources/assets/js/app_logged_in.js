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
    data: {
        guest: false,
        user: {}
    },
    components: {
        App
    },
    mounted() {
        this.$http.get('/api/user')
            .then( response => {
                this.user = response.data;
            } )
            .catch( error => this.user = {} )
    }
}).$mount('#app');