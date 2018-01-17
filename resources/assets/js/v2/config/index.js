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
            // For the time being, we associate each condition with two lab test ids
            // to be displayed on the conditions pages
            labTests: {
                'skin-issues': [9, 10],
                'food-allergies': [9, 10],
                'stress-anxiety': [3, 1],
                'digestive-issues': [10, 9],
                'fatigue': [3, 1],
                'weight-loss-gain': [9, 1],
                'womens-health': [2, 4],
                'general-health': [1, 9]
            },
            subText: {},
        },
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
