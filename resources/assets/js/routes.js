import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: require('./components/Dashboard.vue')
    },
    {
        path: '/new-appointment',
        name: 'new-appointment',
        component: require('./components/NewAppointment.vue')
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