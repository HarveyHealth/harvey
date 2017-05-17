import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: require('./pages/dashboard/Dashboard.vue'),
        children: [
            {
                path: '/new-appointment',
                name: 'new-appointment',
                component: require('./pages/new_appointments/NewAppointmentWrapper.vue')
            }
        ]
    },
    {
        path: '/schedule',
        name: 'schedule',
        component: require('./pages/schedule/Schedule.vue'),
        children: [
            {
                path: 'confirmation',
                name: 'confirmation',
                component: require('./pages/schedule/Confirmation.vue')
            }
        ]
    },
    {
      path: '/signup',
      name: 'signup',
      component: require('./pages/signup/Signup.vue')
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