import misc from './misc';
import time from './time';
import user from './user';

export default function(laravel) {
  return {
    misc: misc(laravel),
    time,
    user: user(laravel),
  }
}
