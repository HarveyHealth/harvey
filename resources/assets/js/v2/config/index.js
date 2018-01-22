import misc from './misc';
import time from './time';
import user from './user';

export default function(laravel) {
    const env = require('get-env')();
    return {
        isDevEnv: env === 'development' || env === 'dev',
        isProduction: env === 'production' || env === 'prod',
        isStageEnv: env === 'staging' || env === 'stage',
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
