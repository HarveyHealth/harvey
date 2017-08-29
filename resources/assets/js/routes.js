import VueRouter from 'vue-router';
Vue.use(VueRouter);

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

// For when intake FE is ready
// rootRoute.name = context === 'get-started'
//   ? 'get-started' : context === 'intake'
//   ? 'intake' : context !== 'get-started' || context !== 'intake'
//   ? 'dashboard' : 'get-started';

// rootRoute.component = context === 'get-started'
//   ? require('./pages/get-started/GetStarted.vue') : context === 'intake'
//   ? require('./pages/intake/Intake.vue') : context !== 'get-started' || context !== 'intake'
//   ? require('./pages/dashboard/Dashboard.vue') : require('./pages/get-started/GetStarted.vue');

rootRoute.name = context === 'get-started'
  ? 'get-started'
  : 'dashboard';

rootRoute.component = context === 'get-started'
  ? require('./pages/get-started/GetStarted.vue')
  : require('./pages/dashboard/Dashboard.vue');

if (context === 'get-started' && loggedIn) {
  rootRoute.children = [
    { path: 'welcome',
      name: 'welcome',
      component: require('./pages/get-started/children/Welcome.vue') },
    { path: 'out-of-range',
      name: 'out-of-range',
      component: require('./pages/get-started/children/OutOfRange.vue') },
    { path: 'practitioner',
      name: 'practitioner',
      component: require('./pages/get-started/children/Practitioner.vue') },
    { path: 'phone',
      name: 'phone',
      component: require('./pages/get-started/children/Phone.vue') },
    { path: 'schedule',
      name: 'schedule',
      component: require('./pages/get-started/children/Schedule.vue') },
    { path: 'confirmation',
      name: 'confirmation',
      component: require('./pages/get-started/children/Confirmation.vue') },
    { path: 'success',
      name: 'success',
      component: require('./pages/get-started/children/Success.vue') }
  ];
} else if (context === 'get-started') {
  rootRoute.children.push({
    path: 'out-of-range',
    name: 'out-of-range',
    component: require('./pages/get-started/children/OutOfRange.vue')
  });
}

rootRoute.children.push({
  path: 'signup',
  name: 'sign-up',
  component: require('./pages/get-started/children/Signup.vue')
})

let routes = [

    rootRoute,

    {
        path: '/appointments',
        name: 'appointments',
        props: true,
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
        path: '/profile',
        component: require('./pages/profile/Profile.vue')
    },
    {
        path: '/profile/:id',
        props: true,
        component: require('./pages/profile/Profile.vue')
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
