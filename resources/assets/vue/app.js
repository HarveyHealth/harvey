import './bootstrap';
import router from './routes';

// FILTERS
import filter_datetime from './utils/filters/datetime';
Vue.filter('datetime', filter_datetime);

// MIXINS
import TopNav from './utils/mixins/TopNav';

// COMPONENETS
import Alert from './commons/Alert.vue';

// Centralized event handler to easily share among components
const eventHub = new Vue();
Vue.prototype.$eventHub = eventHub;

const app = new Vue({
    router,
    mixins: [TopNav],
    components: {
        Alert
    },
    data: {
        guest: false,
        global: {
            user: {},
            appointments: [],
            patients: [],
            test_results:[]
        },
        apiUrl: '/api/alpha'
    },
    mounted() {
        Stripe.setPublishableKey(Laravel.services.stripe.key);

        this.$http.get(this.apiUrl + '/users/' + Laravel.user.id)
            .then( response => {
                this.global.user = response.data.data;
            } )
            .catch( error => this.global.user = {} );

        this.$eventHub.$on('mixpanel', (event) => {
            if (typeof mixpanel !== 'undefined') mixpanel.track(event);
        });
    }
}).$mount('#app');