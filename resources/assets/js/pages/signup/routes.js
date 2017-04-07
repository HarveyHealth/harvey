import VueRouter from 'vue-router';
Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/signup',
      component: require('./Signup.vue')
    },
    {
      path: '/phone',
      component: require('./_components/Phone.vue')
    },
    {
      path: '/location',
      component: require('./_components/Location.vue')
    },
    {
      path: '/practitioner',
      component: require('./_components/Practitioner.vue')
    },
    {
      path: '/datetime',
      component: require('./_components/DateTime.vue')
    },
    {
      path: '/confirmation',
      component: require('./_components/Confirmation.vue')
    },
    {
      path: '/dashboard',
      redirect:  '/'
    }
  ]
})

export default router
