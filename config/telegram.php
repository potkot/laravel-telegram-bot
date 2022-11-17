<?php
return [
    'bot' => [
        'water' => [
            'token' => env('TELEGRAM_BOT_WATER_TOKEN'),
            'request_secret_token' => env('TELEGRAM_BOT_WATER_REQUEST_SECRET_TOKEN'),
            'enabled_commands' => [
                \App\Services\Bot\Commands\StartCommand::class,
                \App\Services\Bot\Commands\HelpCommands::class,
            ]
        ]
    ],
];
