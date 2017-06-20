import Pusher from 'pusher-js';


const socket = new Pusher(`${window.Laravel.services.pusher.key}`, {
  cluster: 'mt1',
  enabledTransports: ['ws', 'xhr_streaming'],
  disabledTransports: ['xhr_streaming'],
  authEndpoint: `/broadcasting/auth`,
  auth: {
    headers: { 'X-CSRF-Token': window.Laravel.app.csrfToken }
  }
});

let channel = socket.subscribe(`private-App.User.${window.Laravel.user.id}`);

export default channel;
