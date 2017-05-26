import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

let routes = [
    {
        path: '/appointments',
        component: require('./pages/appointments/Appointments.vue'),
    },
    {
        path: '/',
        name: localStorage.getItem('signing up') ?
            'signup' : localStorage.getItem('signed up') ?
              'schedule' : 'dashboard',
        component: localStorage.getItem('signing up') ?
            require('./pages/signup/Signup.vue') : localStorage.getItem('signed up') ?
              require('./pages/schedule/Schedule.vue') : require('./pages/dashboard/Dashboard.vue'),
        children: [
            {
                path: 'confirmation',
                component: require('./pages/schedule/Confirmation.vue')
            }
        ]
    },
    {
        path: '/new-appointments',
        component: require('./pages/new_appointments/NewAppointmentWrapper.vue')
    },
    {
        path: '/messages',
        component: require('./pages/messages/Messages.vue')
    },
    {
        path: '/detail/:id',
        props: true,
        component: require('./pages/messages/DetailMessage.vue')
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
