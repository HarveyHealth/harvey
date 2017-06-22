import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

// Logic for managing root components based on the root URL
//    /getting-started -> signup funnel
//    /dashboard -> user backoffice
const loggedIn = Laravel.user.signedIn;

let rootRoute = {
  path: '/',
  name: null,
  component: null,
  children: []
};

// This is the basic logic once the new getting-started components are up
rootRoute.name = loggedIn
  ? 'dashboard'
  : 'signup';

rootRoute.component = loggedIn
  ? require('./pages/dashboard/Dashboard.vue')
  : require('./pages/signup/Signup.vue');

// This code is to manage the current funnel which uses localStorage to save information
if (!window.TestGettingStarted) {
  const signingUp = localStorage.getItem('signing up');
  const signedUp = localStorage.getItem('signed up');
  if (signingUp) {
    rootRoute.name = 'signup';
    rootRoute.component = require('./pages/signup/Signup.vue');
  } else {
    if (signedUp) {
      rootRoute.name = 'schedule';
      rootRoute.component = require('./pages/schedule/Schedule.vue');
      rootRoute.children = [
        { path: 'confirmation',
          component: require('./pages/schedule/Confirmation.vue') }
      ]
    } else {
      rootRoute.name = 'dashboard';
      rootRoute.component = require('./pages/dashboard/Dashboard.vue');
    }
  }
}

let routes = [

    rootRoute,

    {
        path: '/appointments',
        component: require('./pages/appointments/Appointments.vue'),
    },
    {
        path: '/messages',
        component: require('./pages/messages/Messages.vue')
    },
    {
        path: '/detail',
        name: 'detail',
        props: true,
        component: require('./pages/messages/DetailMessage.vue')
    },
    {
        path: '/lab_orders',
        component: require('./pages/lab_orders/LabOrders.vue')
    },
    {
        path: '*',
        redirect:  '/'
    }
];

let router = new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});

router.afterEach(() => {
    if (router.app.nav_is_open) {
        router.app.nav_is_open = false;
    }
});

export default router
