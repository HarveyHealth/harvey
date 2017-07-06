import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

// Logic for managing root components based on the root URL
//    /getting-started -> signup funnel
//    /dashboard -> user backoffice
const loggedIn = Laravel.user.signedIn;
const context = window.$$context;
const rootRedirect = context === 'getting-started'
  ? loggedIn ? '/welcome' : '/signup'
  : '/';

let rootRoute = {
  path: '/',
  name: null,
  component: null,
  children: []
};

// This is the basic logic once the new getting-started components are up
rootRoute.name = context !== 'getting-started'
  ? 'dashboard'
  : 'getting-started';

rootRoute.component = context !== 'getting-started'
  ? require('./pages/dashboard/Dashboard.vue')
  : require('./pages/getting-started/GettingStarted.vue');

if (context === 'getting-started' && loggedIn) {
  rootRoute.children = [
    { path: 'welcome',
      name: 'welcome',
      component: require('./pages/getting-started/children/Welcome.vue') },
    { path: 'out-of-range',
      name: 'out-of-range',
      component: require('./pages/getting-started/children/OutOfRange.vue') },
    { path: 'practitioner',
      name: 'practitioner',
      component: require('./pages/getting-started/children/Practitioner.vue') },
    { path: 'phone',
      name: 'phone',
      component: require('./pages/getting-started/children/Phone.vue') },
    { path: 'schedule',
      name: 'schedule',
      component: require('./pages/getting-started/children/Schedule.vue') },
    { path: 'confirmation',
      name: 'confirmation',
      component: require('./pages/getting-started/children/Confirmation.vue') },
    { path: 'success',
      name: 'success',
      component: require('./pages/getting-started/children/Success.vue') }
  ];
} else if (context === 'getting-started') {
  rootRoute.children.push({
    path: 'out-of-range',
    name: 'out-of-range',
    component: require('./pages/getting-started/children/OutOfRange.vue')
  });
}

rootRoute.children.push({
  path: 'signup',
  name: 'sign-up',
  component: require('./pages/getting-started/children/Signup.vue')
})

// This code is to manage the current funnel which uses localStorage to save information
// if (!window.TestGettingStarted) {
//   const signingUp = localStorage.getItem('signing up');
//   const signedUp = localStorage.getItem('signed up');
//   if (signingUp) {
//     rootRoute.name = 'signup';
//     rootRoute.component = require('./pages/signup/Signup.vue');
//   } else {
//     if (signedUp) {
//       rootRoute.name = 'schedule';
//       rootRoute.component = require('./pages/schedule/Schedule.vue');
//       rootRoute.children = [
//         { path: 'confirmation',
//           component: require('./pages/schedule/Confirmation.vue') }
//       ]
//     } else {
//       rootRoute.name = 'dashboard';
//       rootRoute.component = require('./pages/dashboard/Dashboard.vue');
//     }
//   }
// }

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
        redirect:  rootRedirect
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
