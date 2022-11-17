<?php

namespace App\Services\Bot\Commands;

use App\Services\WaterBaseService;

class StartCommand extends Command
{
    protected int $chatId;
    private string $text;
    private WaterBaseService $waterBaseService;

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

        /**
         * Подключаем сервис для работы с водобазами
         */
        $this->waterBaseService = new WaterBaseService();

        /**
         * Если нужно выполнить какой-то шаг, то выполняем его.
         */
        if ($step && in_array($step, get_class_methods($this))) {
            $this->$step();
            return;
        }

        /**
         * Получаем список регионов и отправляем его в сообщении в виде кнопок
         */
        $keyboardItems = $this->waterBaseService->getRegions();
        $response = $this->telegram->sendMessage($this->chatId, __('Select region: '), ["inline_keyboard" => $keyboardItems]);

        if ($response !== false) {
            /**
             * Ставим отметку, что бот в ожидании и указываем какой шаг следующий
             */
            $this->stateStore->storeState($this->chatId, $this, 'readRegion');
        }
    }

    /**
     * Втрой шаг
     * @return void
     */
    private function readRegion(): void
    {
        $keyboardItems = $this->waterBaseService->getWatersBasesByRegion($this->text);
        $response = $this->telegram->sendMessage($this->chatId, __('Select water base: '), ["inline_keyboard" => $keyboardItems]);
        if ($response !== false) {
            $this->stateStore->storeState($this->chatId, $this, 'readWaterBase');
        }
    }

    /**
     * Третий шаг
     * @return void
     */
    private function readWaterBase(): void
    {
        $waterBase = $this->waterBaseService->getWaterBaseByUUID($this->text);
        $volume = $this->waterBaseService->getVolumeByWaterBase($this->text);

        $message = __($waterBase['name'] . '(' . $waterBase['region'] . ') volume: :volume', ['volume' => $volume]);

        $keyboardItems = [
            [
                [
                    'text' => __('Select region again?'),
                    'callback_data' => '/start'
                ]

            ]
        ];

        $response = $this->telegram->sendMessage($this->chatId, $message, ["inline_keyboard" => $keyboardItems]);
        if ($response !== false) {
            $this->stateStore->clearState($this->chatId, $this);
        }
    }
}
