<?php

namespace App\Services\Bot\Commands;

use App\Services\Bot\BotStateStore;
use App\Services\Telegram;

abstract class Command implements CommandInterface
{

    protected Telegram $telegram;
    protected BotStateStore $stateStore;

    public function __construct(Telegram $telegram, BotStateStore $stateStore)
    {
        $this->telegram = $telegram;
        $this->stateStore = $stateStore;
    }

    /**
     * Возвращает ключ конфига телеграм бота, который запускает команду
     *
     * @return string
     */
    public function getTelegramKey(): string
    {
        return $this->telegram->getKey();
    }

    abstract public static function getName(): string;

    abstract public static function getDescription(): string;

    abstract public function action(int $chatId, $text, $step): void;

}
