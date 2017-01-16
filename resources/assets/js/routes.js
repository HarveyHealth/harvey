import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: require('./components/Dashboard.vue')
    }
];

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});