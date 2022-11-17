<?php

namespace App\Services\Bot\Commands;

/**
 * Команда для показа списка доступных команд
 */
class HelpCommands extends Command
{
    public static function getName(): string
    {
        return 'help';
    }

    public static function getDescription(): string
    {
        return 'Show commands list';
    }

    public function action(int $chatId, $text, $step = false): void
    {
        $commands = [];

        $commandClasses = config('telegram.bot.' . $this->telegram->getKey() . '.enabled_commands', []);

        foreach ($commandClasses as $commandClass) {
            $command = new $commandClass($this->telegram, $this->stateStore);
            $commands[] = [
                'name' => $command::getName(),
                'description' => $command::getDescription()
            ];
        }

        $this->telegram->sendMessage($chatId, view('telegram.help', compact('commands'))->render());
    }
}
