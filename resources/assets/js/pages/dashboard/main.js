import '../../bootstrap';
import router from './routes';




// FILTERS
import filter_datetime from '../../filters/datetime';
Vue.filter('datetime', filter_datetime);

// MIXINS
import TopNav from '../../mixins/TopNav';

// COMPONENETS
import Alert from '../../_includes/Alert.vue';

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
