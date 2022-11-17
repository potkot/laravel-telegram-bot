<?php

namespace App\Services\Bot\Commands;

use App\Services\Bot\BotStateStore;
use App\Services\Telegram;

interface CommandInterface
{

    public function __construct(Telegram $telegram, BotStateStore $stateStore);

    public function getTelegramKey(): string;

    public static function getName(): string;

    public static function getDescription(): string;

    public function action(int $chatId, $text, $step): void;

}
