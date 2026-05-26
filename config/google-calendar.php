<?php

return [
    /*
     * The default auth profile to use.
     */
    'default_auth_profile' => env('GOOGLE_CALENDAR_AUTH_PROFILE', 'service_account'),

    'auth_profiles' => [
        'service_account' => [
            /*
             * Path to the JSON file with the service account credentials.
             */
            'credentials_json' => storage_path('app/google-calendar/service-account-credentials.json'),
        ],
    ],

    /*
     * The Google Calendar ID to use.
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID', 'primary'),
];