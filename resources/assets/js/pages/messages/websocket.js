import Pusher from 'pusher-js';


const socket = new Pusher('921f783fca361d2ac622', {
  cluster: 'mt1',
  enabledTransports: ['ws', 'xhr_streaming'],
  disabledTransports: ['xhr_streaming'],
  authEndpoint: `/broadcasting/auth`,
  auth: {
    headers: { 'X-CSRF-Token': window.Laravel.app.csrfToken }
  }
});

let channel = socket.subscribe(`private-App.User.${Number(window.Laravel.user.id)}`);

export default channel;
