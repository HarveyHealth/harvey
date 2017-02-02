import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: require('./components/dashboard/Dashboard.vue')
    },
    {
        path: '/new-appointment',
        name: 'new-appointment',
        component: require('./components/new_appointment/NewAppointmentWrapper.vue')
    },
    // {
    //     path: '/profile',
    //     component: require('./components/Profile.vue')
    // },
    // {
    //     path: '/payment',
    //     component: require('./components/Payment.vue')
    // },
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