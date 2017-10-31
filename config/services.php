<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'slack' => [
        'webhook_url' => env('SLACK_WEBHOOK_URL'),
        'testing_channel' => env('SLACK_TESTING_CHANNEL', 'testing'),
    ],

    'intakeq' => [
        'api_key' => env('INTAKEQ_API_KEY')
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN', 'POSTMARK_API_TEST'),
        'signature' => env('POSTMARK_SIGNATURE', 'hello@goharvey.com'),
        'templates' => [
            'password' => [
                'reset' => 1497641,
            ],
            'visitor' => [
                'subscribe' => 2861223,
            ],
            'practitioner' => [
                'appointment' => [
                    'canceled' => 1692581,
                    'new' => 1529541,
                    'updated' => 1929883,
                    'reminder' => 2550321,
                ],
                'attachment' => [
                    'uploaded' => 3338765,
                ],
            ],
            'patient' => [
                'appointment' => [
                    'canceled' => 1687742,
                    'complete' => 3376781,
                    'new' => 1492142,
                    'updated' => 1929884,
                    'reminder' => 1497642,
                ],
                'charge' => [
                    'failed' => 3372981,
                ],
                'lab_order' => [
                    'confirmed' => 3372143,
                    'shipped' => 2741642,
                ],
                'lab_test' => [
                    'processing' => 3148942,
                ],
                'welcome' => 1450461,
            ],
            'message' => [
                'unread' => 2495382,
            ],
        ],
    ],

    'timezonedb' => [
        'key' => env('TIMEZONEDB_KEY'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_ACCOUNT_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'sms_number' => env('TWILIO_SMS_NUMBER'),
    ],

    'google_calendar' => [
        'client_secret_file' => env('GCALENDAR_SECRET_FILE', storage_path('calendar_api/client_secret.json')),
        'access_token_file' => env('GCALENDAR_TOKEN_FILE', storage_path('calendar_api/access_token.json')),
        'calendar_id' => env('GCALENDAR_ID', 'goharvey.com_52ld7v7p6tpep95idupudk3b70@group.calendar.google.com'),
    ],

    'google_geocoder' => [
        'api_key' => env('GOOGLE_GEOCODER_API_KEY'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_ID'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect'      => env('FACEBOOK_URL'),
    ],

    'segment' => [
        'key'           => env('SEGMENT_KEY'),
    ]
];
