<?php

return [
    // Only used by AdminSeeder; credentials must never be committed to Git.
    'seed_email' => env('ADMIN_SEED_EMAIL'),
    'seed_password' => env('ADMIN_SEED_PASSWORD'),
    'seed_name' => env('ADMIN_SEED_NAME', 'Administrator'),
];
