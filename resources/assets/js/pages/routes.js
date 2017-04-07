import VueRouter from 'vue-router';
Vue.use(VueRouter);

const router = new VueRouter({
  // mode: 'history',
  routes: [
    {
      path: '/',
      component: require('./signup/Signup.vue')
    },
    {
      path: '/get-started',
      component: require('./signup/GetStarted.vue')
    }
  ]
})

export default router
