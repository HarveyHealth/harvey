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

    'fullscript' => [
        'clinic_key' => env('FULLSCRIPT_CLINIC_KEY'),
        'api_key' => env('FULLSCRIPT_API_KEY'),
        'api_host' => env('FULLSCRIPT_API_HOST'),
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
                'lab_test_result' => [
                    'created' => 4099061,
                ],
                'attachment' => [
                    'created' => 3338765,
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
                    'recommended' => 3764861,
                    'reminder' => 3946483,
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
        'feedback_number' => env('TWILIO_FEEDBACK_NUMBER'),
    ],

    'google_calendar' => [
        'client_secret_file' => env('GCALENDAR_SECRET_FILE', storage_path('calendar_api/client_secret.json')),
        'access_token_file' => env('GCALENDAR_TOKEN_FILE', storage_path('calendar_api/access_token.json')),
        'calendar_id' => env('GCALENDAR_ID', 'goharvey.com_52ld7v7p6tpep95idupudk3b70@group.calendar.google.com'),
    ],

    'shippo' => [
        'key' => env('SHIPPO_API_KEY'),
        'default_carrier' => env('SHIPPO_DEFAULT_CARRIER', 'fedex'),
        'default_carrier_service_level' => env('SHIPPO_DEFAULT_CARRIER_SERVICE_LEVEL', 'fedex_2_day'),
        'from' => [
            'name' => env('SHIPPO_FROM_NAME', 'Harvey, Inc'),
            'company' => env('SHIPPO_FROM_COMPANY', 'Harvey, Inc'),
            'street1' => env('SHIPPO_FROM_STREET1', '12655 West Jefferson Boulevard'),
            'street2' => env('SHIPPO_FROM_STREET2', 'Suite #3-180'),
            'city' => env('SHIPPO_FROM_CITY', 'Los Angeles'),
            'state' => env('SHIPPO_FROM_STATE', 'CA'),
            'zip' => env('SHIPPO_FROM_ZIP', '90066-7008'),
            'country' => env('SHIPPO_FROM_COUNTRY', 'US'),
            'phone' => env('SHIPPO_FROM_PHONE', '+18006909989'),
            'email' => env('SHIPPO_FROM_EMAIL', 'support@goharvey.com'),
        ],
        'lab_order_box_height_in' => env('SHIPPO_LAB_ORDER_BOX_HEIGHT_IN', 10),
        'lab_order_box_length_in' => env('SHIPPO_LAB_ORDER_BOX_LENGTH_IN', 12),
        'lab_order_box_width_in' => env('SHIPPO_LAB_ORDER_BOX_WIDTH_IN', 4),
        'lab_order_box_weight_lb' => env('SHIPPO_LAB_ORDER_BOX_WEIGHT_LB', 2),
    ],

    'typeform' => [
        'api_key' => env('TYPEFORM_API_KEY'),
        'uid' => env('TYPEFORM_UID'),
    ],

    'google_geocoder' => [
        'api_key' => env('GOOGLE_GEOCODER_API_KEY'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_ID'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect' => env('FACEBOOK_URL'),
    ],

    'segment' => [
        'key' => env('SEGMENT_KEY'),
    ],

    'intercom' => [
        'key' => env('INTERCOM_KEY'),
    ],
];
