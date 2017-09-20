import misc from './misc';
import time from './time';
import user from './user';

export default function(laravel) {
  const env = require('get-env')();
  return {
    isProduction: env === 'production' || env === 'prod',
    misc: misc(laravel),
    time,
    user: user(laravel),
  }
}
