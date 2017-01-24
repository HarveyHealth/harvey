import './bootstrap';

import VueRouter from 'vue-router';
Vue.use(VueRouter);
import router from './routes';

// filters
import filter_datetime from './filters/datetime';
Vue.filter('datetime', filter_datetime);

// components
import App from './components/App.vue';

// event handler
const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

const app = new Vue({
    router,
    data: {
        guest: false,
        user: {},
        userId: '',
        apiUrl: '/api/alpha'
    },
    components: {
        App
    },
    mounted() {
        Stripe.setPublishableKey('pk_test_V6rezd1WTJiBPZaN5qbNyM6U');

        this.userId = Laravel.userId;

        this.$http.get(this.apiUrl + '/users/' + this.userId)
            .then( response => {
                this.user = response.data.data;
            } )
            .catch( error => this.user = {} );

        this.$eventHub.$on('mixpanel', (event) => {
            if (typeof mixpanel !== 'undefined') mixpanel.track(event);
        });
    }
}).$mount('#app');