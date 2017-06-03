import Pusher from 'pusher-js';


const socket = new Pusher('921f783fca361d2ac622', {
  cluster: 'mt1',
  enabledTransports: ['ws', 'xhr_streaming'],
  disabledTransports: ['xhr_streaming'],
  authEndpoint: `http://harvey.app/oauth/token`,
  encrypted: true,
  auth: {
    headers: { 'X-CSRF-Token': window.Laravel.app.csrfToken }
  }
});

export default socket;
