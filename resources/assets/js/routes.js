import VueRouter from 'vue-router';

// Logic for managing root components based on the root URL
//    /getting-started -> signup funnel
//    /intake -> intake form
//    /dashboard -> user backoffice
const loggedIn = Laravel.user.signedIn;
const context = window.$$context;

const rootRedirect = context === 'get-started'
  ? loggedIn ? '/welcome' : '/signup'
  : '/';

let rootRoute = {
  path: '/',
  name: null,
  component: null,
  children: []
};

switch(context) {
  case 'get-started':
    rootRoute.name = 'get-started';
    rootRoute.component = require('./v2/components/pages/getstarted/GetStarted.vue');
    break;
  case 'dashboard':
    rootRoute.name = 'dashboard';
    rootRoute.component = require('./v2/components/pages/dashboard/Dashboard');
    break;
  case 'conditions':
    rootRoute.name = 'conditions';
    rootRoute.component = require('./v2/components/pages/conditions/Conditions.vue');
    break;
  case 'intake':
    rootRoute.name = 'intake';
    rootRoute.component = require('./v2/components/pages/intake/Intake.vue');
    break;
}

if (context === 'get-started' && loggedIn) {
  rootRoute.children = [
    { path: 'welcome',
      name: 'welcome',
      component: require('./v2/components/pages/getstarted/Welcome.vue') },
    { path: 'practitioner',
      name: 'practitioner',
      component: require('./v2/components/pages/getstarted/Practitioner.vue') },
    { path: 'phone',
      name: 'phone',
      component: require('./v2/components/pages/getstarted/Phone.vue') },
    { path: 'schedule',
      name: 'schedule',
      component: require('./v2/components/pages/getstarted/Schedule.vue') },
    { path: 'payment',
      name: 'payment',
      component: require('./v2/components/pages/getstarted/Payment.vue') },
    { path: 'confirmation',
      name: 'confirmation',
      component: require('./v2/components/pages/getstarted/Confirmation.vue') },
    { path: 'success',
      name: 'success',
      component: require('./v2/components/pages/getstarted/Success.vue') }
  ];
}

if (context === 'get-started') {
    rootRoute.children.push({
      path: 'signup',
      name: 'sign-up',
      component: require('./v2/components/pages/getstarted/Signup.vue')
    });
}

let routes = [
    rootRoute,
    {
        path: '/appointments',
        name: 'appointments',
        props: true,
        component: require('./pages/appointments/Appointments.vue')
    },
    {
        path: '/messages',
        component: require('./pages/messages/Messages.vue')
    },
    {
        path: '/detail/:path',
        name: 'detail',
        props: true,
        component: require('./pages/messages/DetailMessage.vue')
    },
    {
        path: '/lab_orders',
        component: require('./pages/lab_orders/LabOrders.vue')
    },
    {
        path: '/clients',
        component: require('./pages/clients/Clients.vue')
    },
    {
        path: '/records',
        component: require('./pages/records/Records.vue')
    },
    {
        path: '/settings',
        component: require('./pages/settings/Settings.vue')
    },
    {
        path: '/settings/:id',
        component: require('./pages/settings/Settings.vue')
    },
    {
        path: '/profile',
        component: require('./pages/profile/Profile.vue')
    },
    {
        path: '/profile/:id',
        props: true,
        component: require('./pages/profile/Profile.vue')
    },
    {
        path: '/lab_tests/edit',
        props: true,
        component: require('./pages/sku-dashboard/SkuDashboard.vue')
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

export default router;
