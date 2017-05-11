import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: require('./components/dashboard/Dashboard.vue'),
        children: [
            {
                path: 'schedule',
                component: require('./Schedule.vue'),
                children: [
                    {
                        path: 'confirmation',
                        component: require('./Confirmation.vue'),
                    }
                ]
            },
            {
                path: '/new-appointment',
                name: 'new-appointment',
                component: require('./components/new_appointment/NewAppointmentWrapper.vue')
            }
        ]
    },
    {
      path: '/signup',
      component: require('./Signup.vue'),
    },
    {
        path: '*',
        redirect:  '/'
    }
];

let router = new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'is-active'
});

router.afterEach(() => {
    if (router.app.nav_is_open) {
        router.app.nav_is_open = false;
    }
});

export default router