<?php

namespace App\Services\Bot;

use App\Http\Requests\TelegramWebhookRequest;
use App\Services\Bot\Commands\CommandInterface;
use App\Services\Telegram;

/**
 * Класс обрабатывает webhook от телеграм вызывает необходимы команды
 */
class Bot
{
    private Telegram $telegram;
    private BotStateStore $stateStore;

    public function __construct(BotStateStore $stateStore)
    {
        $this->stateStore = $stateStore;
    }

    /**
     * Добавляем объект телеграмма с которым будет работать бот
     *
     * @param Telegram $telegram
     * @return void
     */
    public function setTelegram(Telegram $telegram): void
    {
        $this->telegram = $telegram;
    }

    /**
     * Обработка webhook телеграм
     *
     * @param TelegramWebhookRequest $request
     * @return void
     */
    public function handle(TelegramWebhookRequest $request): void
    {
        $text = $request->json('message.text');
        $chatId = $request->json('message.chat.id');

        /**
         * Проверяем, находится ли чат в режиме ожидания действий пользователя
         * @var $waiting BotStateStore
         */
        $waiting = $this->stateStore->waitingAction($this->telegram->getKey(), $chatId);

        if ($waiting) {
            /**
             * Если в режиме ожидания, то вызываем определенный шаг команды
             */
            $command = new $this->stateStore->command($this->telegram, $this->stateStore);
            $command->action($chatId, $text, $this->stateStore->step);
            return;
        }

        /**
         * Если не в режиме ожидания, то ищем команду для выполнения
         *
         * @var $command CommandInterface
         */
        $command = $this->findCommand($text);

        if ($command) {
            $command->action($chatId, $text);
        } else {
            $this->telegram->sendMessage($chatId, __('Command not found'));
        }
    }

    /**
     * Поиск команды в сообщении
     * @param $text
     * @return CommandInterface|null
     */
    private function findCommand($text): CommandInterface|null
    {
        $commandClasses = config('telegram.bot.' . $this->telegram->getKey() . '.enabled_commands', []);

        foreach ($commandClasses as $commandClass) {
            $command = new $commandClass($this->telegram, $this->stateStore);
            if ("/" . $command::getName() === $text && $command instanceof CommandInterface) {
                return $command;
            }
        }

        return null;
    }
}
