import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

Vue.use(VueRouter);
Vue.use(VeeValidate);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/signup',
      component: require('./signup/Signup.vue'),
    },
    {
      path: '/get-started',
      component: require('./signup/GetStarted.vue'),
    }
  ]
})

export default router
