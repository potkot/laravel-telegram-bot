<?php

namespace App\Services\Bot\Commands;

class StartCommand extends Command
{
    protected int $chatId;
    private string $text;

    public static function getName(): string
    {
        return 'start';
    }

    public static function getDescription(): string
    {
        return 'Start search water';
    }

    /**
     * Основной действие команды
     * Если команда в ожидании действий пользователя, то выбирает и запускает следующий шаг
     *
     * @param int $chatId
     * @param $text
     * @param $step
     * @return void
     */
    public function action(int $chatId, $text, $step = false): void
    {
        $this->chatId = $chatId;
        $this->text = $text;

        if ($step && in_array($step, get_class_methods($this))) {
            $this->$step();
            return;
        }

        $this->telegram->sendMessage($this->chatId, __('Enter region: '));
        $this->stateStore->storeState($this->chatId, $this, 'readRegion');
    }

    /**
     * Втрой шаг
     * @return void
     */
    private function readRegion()
    {
        $this->telegram->sendMessage($this->chatId, __('Enter water base: '));
        $this->stateStore->storeState($this->chatId, $this, 'readWaterBase');
    }

    /**
     * Третий шаг
     * @return void
     */
    private function readWaterBase()
    {
        $this->telegram->sendMessage($this->chatId, __('Water volumes list'));
        $this->stateStore->clearState($this->chatId, $this);
    }
}
