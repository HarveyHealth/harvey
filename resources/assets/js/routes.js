import VueRouter from 'vue-router';

let routes = [
    {
        path: '/home',
        component: require('./components/Example.vue')
    },
    // {
    //     path: '/account',
    //     component: require('./components/Account.vue'),
    //     children: [
    //      {
    //         path: 'example',
    //         component: require('./components/Example.vue'),
    //      }
    //     ]
    // }
];

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});