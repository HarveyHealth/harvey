import misc from './misc';
import time from './time';
import user from './user';

export default function(laravel) {
  const env = require('get-env')();
  return {
    isProduction: env === 'production' || env === 'prod',
    misc: misc(laravel),
    support: {
      email: 'support@goharvey.com',
      name: 'Sandra Walker',
      phone: '800-690-9989',
      available: 'Weekdays 9am - 6pm PST'
    },
    time,
    user: user(laravel),
  }
}
