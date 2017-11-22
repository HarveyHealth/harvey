import misc from './misc';
import time from './time';
import user from './user';

export default function(laravel) {
    const env = require('get-env')();
    return {
        conditions: {
            backgrounds: {
                'skin-issues': 'lime',
                'food-allergies': 'pink',
                'stress-anxiety': 'brown',
                'digestive-issues': 'green',
                'fatigue': 'turquoise',
                'weight-loss-gain': 'slate',
                'womens-health': 'purple',
                'general-health': 'ford'
            },
            subText: {},
        },
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
