/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */
require('es6-promise').polyfill();

import 'babel-polyfill';
import Vue from 'vue';
import Axios from 'axios';

window.Vue = Vue;
window.axios = Axios;
Vue.prototype.$http = Axios;

/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */

Axios.interceptors.request.use(function(config){
    // Attach the csrf token to every request in the header
    config.headers['X-CSRF-TOKEN'] = Laravel.app.csrfToken;


    // Notifies the back-end that this is an ajax request
    config.headers['X-Requested-With'] = 'XMLHttpRequest';

    return config;
});

// Social Capital Pixel Init code
(function(p,l,o,w,i,n,g){if(!p[i]){p.GlobalSnowplowNamespace=p.GlobalSnowplowNamespace||[];
  p.GlobalSnowplowNamespace.push(i);p[i]=function(){(p[i].q=p[i].q||[]).push(arguments);
  };p[i].q=p[i].q||[];n=l.createElement(o);g=l.getElementsByTagName(o)[0];n.async=1;
  n.src=w;g.parentNode.insertBefore(n,g);}}(window,document,"script","https://datacoral.com/instrumentation/js/1.0.0/dc.js","datacoral"));

window.datacoral('newTracker', 'anther', 'events.anther.io', {
    appId: 'harvey',
    platform : 'desktop',
    cookieName: 'dc',
    apiKey : 'RgYaIynBRBzhrlZZQZCWIKcXvPtHP5UeRkOK3tZ3',
    datacoralEnv: 'prod',
    forceSecureTracker : true,
    post: true,
    contexts: {
        webPage: true,
        gaCookies: true
    }
});


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key',
//     cluster: 'mt1',
//     encrypted: true
// });
