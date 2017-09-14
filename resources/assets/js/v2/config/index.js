import appointments from './appointments';
import dashboard from './dashboard';
import misc from './misc';
import time from './time';
import user from './user';

export default function(laravel) {
  return {
    appointments,
    dashboard,
    misc: misc(laravel),
    time,
    user: user(laravel),
  }
}
