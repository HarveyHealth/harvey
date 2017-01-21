import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./components/Dashboard.vue')
    },
    {
        path: '/new-appointment',
        component: require('./components/NewAppointmentWrapper.vue')
    },
    {
        path: '/profile',
        component: require('./components/Profile.vue')
    },
    {
        path: '/payment',
        component: require('./components/Payment.vue')
    },
    {
        path: '*',
        redirect:  '/'
    }
];

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});