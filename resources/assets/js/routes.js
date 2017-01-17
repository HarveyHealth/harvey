import VueRouter from 'vue-router';

let routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: require('./components/Dashboard.vue')
    },
    {
        path: '*',
        redirect:  '/dashboard'
    }
];

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});