import colors from './colors';
import regulatedStates from './regulatedStates';

export default function (laravel) {
  return {
    api: '/api/v1/',
    colors,
    debug: true,
    defaultUserImage: '/images/default_user_image.png',
    environment: require('get-env')(),
    guest: false,
    intakeLink: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${laravel.user.id}`,
    regulatedStates,
  }
}
