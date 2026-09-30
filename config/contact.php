<?php

return [
    'email' => [
        'to' => env('CONTACT_EMAIL_TO'),
        'from' => env('CONTACT_EMAIL_FROM'),
        'from_name' => env('CONTACT_EMAIL_FROM_NAME', 'M. Natsir Kongah'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
        'endpoint' => 'https://api.resend.com/emails',
    ],

    'whatsapp_url' => env('CONTACT_WHATSAPP_URL'),
    'cv_url' => env('CONTACT_CV_URL'),
];
