<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ga4' => [
        'tracking_id' => env('GA4_TRACKING_ID', ''),
    ],

    'search_console' => [
        'verify' => env('SEARCH_CONSOLE_VERIFY', ''),
    ],

    'facebook' => [
        // Facebook App ID az fb:app_id meta taghez. Ez NYILVÁNOS érték (a forrásban is
        // látszik), nem titok. (Az App Secret ettől külön, titkos érték - azt nem használjuk.)
        'app_id' => env('FB_APP_ID', '1002346442669781'),

        // A poszt-szöveg előtöltése (Share Dialog + quote) csak akkor kapcsoljon be, ha az
        // app Live módban van ÉS a fogaszatmiskolc.com domain fel van véve az appban
        // (App Domains + Website platform). Enélkül a látogatóknak hibás lenne a dialógus,
        // ezért alapból ki van kapcsolva; ha kész a beállítás, FB_SHARE_DIALOG=true.
        'share_dialog' => (bool) env('FB_SHARE_DIALOG', false),
    ],

];
