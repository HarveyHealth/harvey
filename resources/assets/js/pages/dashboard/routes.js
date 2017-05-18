import VueRouter from 'vue-router';
Vue.use(VueRouter);

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: require('./Dashboard.vue')
    },
    {
        path: '/new-appointment',
        name: 'new-appointment',
        component: require('../../new_appointment/NewAppointment.vue')
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
