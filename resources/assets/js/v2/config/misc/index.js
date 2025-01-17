import colors from './colors';
import regulatedStates from './regulatedStates';
import socialMedia from './socialMedia';

export default function (laravel) {
  return {
    api: '/api/v1/',
    breakpoints: {
        s: 0,
        ns: 420,
        m: 640,
        l: 780,
        xl: 960,
        xxl: 1280
    },
    colors,
    currentPage: null,
    defaultUserImage: '/images/default_user_image.png',
    environment: require('get-env')(),
    gridRowId: 0,
    guest: false,
    intakeLink: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${laravel.user.id}`,
    regulatedStates,
    socialMedia,
    spacing: [0, 0.25, 0.7, 1.3, 2, 4, 8, 16]
  }
}
