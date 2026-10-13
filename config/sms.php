<?php

return [
    // log (default, writes to storage/logs) | http (real gateway)
    'driver' => env('SMS_DRIVER', 'log'),
    'log_channel' => env('SMS_LOG_CHANNEL', 'stack'),
    'http' => [
        'url' => env('SMS_API_URL', 'https://bulksmsbd.net/api/smsapi'),
        'api_key' => env('SMS_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID'),
        'params' => ['api_key' => 'api_key', 'sender' => 'senderid', 'to' => 'number', 'message' => 'message'],
    ],
];
