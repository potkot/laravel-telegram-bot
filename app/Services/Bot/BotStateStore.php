<?php

namespace App\Services\Bot;

use App\Models\BotCommandState;
use App\Services\Bot\Commands\CommandInterface;

/**
 * Работа со статусом команды бота
 */
class BotStateStore
{
    public string $command;
    public string|null $step;

    public function waitingAction($bot, $chatId): bool
    {
        $state = BotCommandState::where('bot', $bot)->where('chat_id', $chatId)->first();
        if ($state) {
            $this->command = $state->command;
            $this->step = $state->step;
            return true;
        }

        return false;
    }

    /**
     * Сохраняет состояние команды
     *
     * @param $chatId
     * @param CommandInterface $command
     * @param string|null $step Следующий шаг, который будет выполнен после действий пользователя
     * @return void
     */
    public function storeState($chatId, CommandInterface $command, string $step = null): void
    {
        BotCommandState::updateOrCreate(
            [
                'bot' => $command->getTelegramKey(),
                'chat_id' => $chatId
            ],
            [
                'command' => get_class($command),
                'step' => $step
            ]);
    }

    public function clearState($chatId, CommandInterface $command,): void
    {
        BotCommandState::where('chat_id', $chatId)->where('bot', $command->getTelegramKey())->delete();
    }
}
