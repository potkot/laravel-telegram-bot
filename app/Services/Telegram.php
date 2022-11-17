<?php

namespace App\Services;

use App\Exceptions\TelegramException;
use Illuminate\Support\Facades\Http;

/**
 * Класс для работы с API телеграм
 */
class Telegram
{
    const TELEGRAM_BASE_URL = 'https://api.telegram.org/bot';

    private Http $http;
    public string $response;
    private string $key;
    private string $token;

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function setKey(string $key): void
    {
        if (!config('telegram.bot.' . $key)) {
            throw new TelegramException(__('Not found config with key «:key»', ['key' => $key]));
        }

        $this->key = $key;
        $this->token = config('telegram.bot.' . $key . '.token');
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function sendMessage(int $chatId, string $message): bool|array
    {
        $data = [
            "chat_id" => $chatId,
            "text" => $message,
            "parse_mode" => "HTML"
        ];

        return $this->sendPostRequest('sendMessage', $data);
    }

    public function setWebhook(string $url, string $secretToken): bool|array
    {
        return $this->sendPostRequest('setWebhook', ['url' => $url, 'secret_token' => $secretToken]);
    }

    public function setMyCommands(): bool|array
    {
        $data = [
            "commands" => []
        ];

        $commandClasses = config('telegram.bot.water.enabled_commands', []);

        foreach ($commandClasses as $commandClass) {
            if (class_exists($commandClass)) {
                $data['commands'][] = [
                    "command" => $commandClass::getName(),
                    "description" => $commandClass::getDescription(),
                ];
            }
        }

        return $this->sendPostRequest('setMyCommands', $data);
    }

    /**
     * Отправить POST запрос
     * @param string $method
     * @param array $data
     * @return \Illuminate\Http\Client\Response|bool
     */
    private function sendPostRequest(string $method, array $data): bool|array
    {
        $response = $this->http::post($this->getUrl($method), $data);
        if ($response->successful()) {
            return $response->json();
        }

        $this->response = $response->body();

        return false;
    }

    /**
     * Сформирует и вернет полный url для указанного метода telegram api
     *
     * @param string $method
     * @return string
     */
    private function getUrl(string $method): string
    {
        return self::TELEGRAM_BASE_URL . $this->token . '/' . $method;
    }

}
