<?php

return [

    'default_auth_profile' => 'service_account',

    'auth_profiles' => [

        'service_account' => [

            /*
             * path to service account credentials json file
             */
            'credentials_json' => storage_path('app/google-calendar/service-account-credentials.json'),

        ],

    ],

    /*
     * Google Calendar ID
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),

];
