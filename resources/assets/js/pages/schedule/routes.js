import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/schedule',
      component: require('./Schedule.vue'),
    },
  ]
})

export default router;
