<?php

return [
    // When true and gateway keys are missing, payments are simulated (local/demo only).
    'simulate' => env('PAYMENT_SIMULATE', true),
    'currency' => 'BDT',

    'sslcommerz' => [
        'store_id' => env('SSLCZ_STORE_ID'),
        'store_password' => env('SSLCZ_STORE_PASSWORD'),
        'sandbox' => env('SSLCZ_SANDBOX', true),
        'sandbox_url' => 'https://sandbox.sslcommerz.com',
        'live_url' => 'https://securepay.sslcommerz.com',
    ],

    'bkash' => [
        'app_key' => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username' => env('BKASH_USERNAME'),
        'password' => env('BKASH_PASSWORD'),
        'sandbox' => env('BKASH_SANDBOX', true),
        'sandbox_url' => 'https://tokenized.sandbox.bka.sh/v1.2.0-beta',
        'live_url' => 'https://tokenized.pay.bka.sh/v1.2.0-beta',
    ],
];
