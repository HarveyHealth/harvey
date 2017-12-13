import colors from './colors';
import regulatedStates from './regulatedStates';
import socialMedia from './socialMedia';

export default function (laravel) {
  return {
    api: '/api/v1/',
    breakpoints: {
        ns: 420,
        m: 640,
        l: 780,
        xl: 960
    },
    colors,
    currentPage: null,
    defaultUserImage: '/images/default_user_image.png',
    environment: require('get-env')(),
    gridRowId: 0,
    guest: false,
    intakeLink: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${laravel.user.id}`,
    regulatedStates,
    socialMedia
  }
}
