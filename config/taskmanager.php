<?php
return [
    'google_analytics_url' => env('GOOGLE_ANALYTICS_URL', 'https://www.google-analytics.com/collect'),
    'web_url' => 'https://www.invoiceninja.com',
    'app_name' => env('APP_NAME'),
    'site_url' => env('APP_URL', ''),
    'app_domain' => env('APP_DOMAIN', 'invoiceninja.com'),
    'app_version' => '0.0.1',
    'api_version' => '0.0.1',
    'terms_version' => '1.0.1',
    'app_env' => env('APP_ENV', 'development'),
    'api_secret' => env('API_SECRET', ''),
    'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
    'key_length' => 64,
    'date_format' => 'Y-m-d',
    'date_time_format' => 'Y-m-d H:i',
    'daily_email_limit' => 300,
    'error_email' => env('ERROR_EMAIL', ''),
    'environment' => env('NINJA_ENVIRONMENT', 'selfhost'), // 'hosted', 'development', 'selfhost', 'reseller'
    // Settings used by invoiceninja.com
    'terms_of_service_url' => [
        'hosted' => env('TERMS_OF_SERVICE_URL', 'https://www.invoiceninja.com/terms/'),
        'selfhost' => env('TERMS_OF_SERVICE_URL', 'https://www.invoiceninja.com/self-hosting-terms-service/'),
    ],
    'privacy_policy_url' => [
        'hosted' => env('PRIVACY_POLICY_URL', 'https://www.invoiceninja.com/privacy-policy/'),
        'selfhost' => env('PRIVACY_POLICY_URL', 'https://www.invoiceninja.com/self-hosting-privacy-data-control/'),
    ],
    'db' => [
        'multi_db_enabled' => env('MULTI_DB_ENABLED', false),
        'default' => env('DB_CONNECTION', 'mysql'),
    ],
    'i18n' => [
        'timezone_id' => env('DEFAULT_TIMEZONE', 1),
        'country_id' => env('DEFAULT_COUNTRY', 840), // United Stated
        'currency_id' => env('DEFAULT_CURRENCY', 1),
        'language_id' => env('DEFAULT_LANGUAGE', 1), //en
        'date_format_id' => env('DEFAULT_DATE_FORMAT_ID', '1'),
        'datetime_format_id' => env('DEFAULT_DATETIME_FORMAT_ID', '1'),
        'locale' => env('DEFAULT_LOCALE', 'en'),
        'map_zoom' => env('DEFAULT_MAP_ZOOM', 10),
        'payment_terms' => env('DEFAULT_PAYMENT_TERMS', 1),
        'military_time' => env('MILITARY_TIME', 0),
        'first_day_of_week' => env('FIRST_DATE_OF_WEEK', 0),
        'first_month_of_year' => env('FIRST_MONTH_OF_YEAR', '2000-01-01')
    ],
    'testvars' => [
        'username' => 'user@example.com',
        'clientname' => 'client@example.com',
        'password' => 'password',
        'stripe' => env('STRIPE_KEYS', ''),
        'paypal' => env('PAYPAL_KEYS', ''),
        'travis' => env('TRAVIS', false),
        'test_email' => env('TEST_EMAIL', ''),
    ],
    'contact' => [
        'email' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
        'ninja_official_contact' => env('NINJA_OFFICIAL_CONTACT', 'michaelhamptondesign@yahoo.com'),
    ],
    'cached_tables' => [
        'banks' => 'App\Models\Bank',
        'countries' => 'App\Models\Country',
        'currencies' => 'App\Models\Currency',
        'date_formats' => 'App\Models\DateFormat',
        'datetime_formats' => 'App\Models\DatetimeFormat',
        'gateways' => 'App\Models\Gateway',
        'gateway_types' => 'App\Models\GatewayType',
        'industries' => 'App\Models\Industry',
        'languages' => 'App\Models\Language',
        'payment_types' => 'App\Models\PaymentType',
        'sizes' => 'App\Models\Size',
        'timezones' => 'App\Models\Timezone',
        //'invoiceDesigns' => 'App\Models\InvoiceDesign',
        //'invoiceStatus' => 'App\Models\InvoiceStatus',
        //'frequencies' => 'App\Models\Frequency',
        //'fonts' => 'App\Models\Font',
    ],
    'notification' => [
        'slack' => env('SLACK_WEBHOOK_URL', ''),
    ],
    'payment_terms' => [
        [
            'num_days' => 0,
            'name' => '',
        ],
        [
            'num_days' => 7,
            'name' => '',
        ],
        [
            'num_days' => 10,
            'name' => '',
        ],
        [
            'num_days' => 14,
            'name' => '',
        ],
        [
            'num_days' => 15,
            'name' => '',
        ],
        [
            'num_days' => 30,
            'name' => '',
        ],
        [
            'num_days' => 60,
            'name' => '',
        ],
        [
            'num_days' => 90,
            'name' => '',
        ]
    ],
];