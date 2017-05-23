import './bootstrap';
import router from './routes';

// FILTERS
import filter_datetime from './filters/datetime';
Vue.filter('datetime', filter_datetime);

// MIXINS
import TopNav from './mixins/TopNav';

// COMPONENETS
import Alert from './components/_includes/Alert.vue';

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

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-89414173-1', 'auto');
        ga('send', 'pageview');
    }
}).$mount('#app');