<?php

return [
    'ticket_prefix' => env('CONTACT_TICKET_PREFIX', 'ISS'), // stand for ISSUE
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],
];
