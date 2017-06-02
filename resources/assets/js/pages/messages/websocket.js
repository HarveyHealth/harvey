import Pusher from 'pusher-js';

const socket = new Pusher('921f783fca361d2ac622', {
  cluster: 'mt1',
  enabledTransport: ['wss']
});

export default socket;
