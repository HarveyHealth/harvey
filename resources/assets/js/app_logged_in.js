import './bootstrap';
import router from './routes';

// filters
import filter_datetime from './filters/datetime';
Vue.filter('datetime', filter_datetime);

// mixins
import TopNav from './mixins/TopNav';

// components
import Alert from './components/_includes/Alert.vue';

// event handler
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
        user: {},
        userId: '',
        apiUrl: '/api/alpha'
    },
    mounted() {
        Stripe.setPublishableKey(Laravel.services.stripe.key);

        this.userId = Laravel.user.id;

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