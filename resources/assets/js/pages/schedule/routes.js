import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

const router = new VueRouter({
  routes: [
    {
      path: '/',
      component: require('./Schedule.vue'),
    },
    {
      path: '/confirmation',
      component: require('./Confirmation.vue'),
    },
  ]
})

export default router;
