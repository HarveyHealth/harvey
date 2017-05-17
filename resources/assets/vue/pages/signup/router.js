import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/signup',
      component: require('./Signup.vue'),
    }
  ]
})

export default router;