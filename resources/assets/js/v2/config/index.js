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
                'skin-issues': ['Food Allergy', 'Microbiome (Gut)'],
                'food-allergies': ['Food Allergy', 'Microbiome (Gut)'],
                'stress-anxiety': ['Adrenals', 'Micronutrients'],
                'digestive-issues': ['Microbiome (Gut)', 'Food Allergy'],
                'fatigue': ['Adrenals', 'Micronutrients'],
                'weight-loss-gain': ['Food Allergy', 'Micronutrients'],
                'womens-health': ['Hormones', 'Thyroid/Cortisol'],
                'general-health': ['Micronutrients', 'Food Allergy']
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
