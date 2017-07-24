import Pusher from 'pusher-js';


const socket = new Pusher(process.env.PUSHER_APP_KEY, {
  cluster: 'mt1',
  enabledTransports: ['ws', 'xhr_streaming'],
  disabledTransports: ['xhr_streaming'],
  authEndpoint: `/broadcasting/auth`,
  auth: {
    headers: { 'X-CSRF-Token': window.Laravel.app.csrfToken }
  }
});

export default socket;
