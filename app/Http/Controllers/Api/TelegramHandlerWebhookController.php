<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TelegramException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TelegramWebhookRequest;
use App\Services\Bot\Bot;
use App\Services\Telegram;

class TelegramHandlerWebhookController extends Controller
{
    public function __invoke(TelegramWebhookRequest $request, Telegram $telegram, Bot $bot)
    {
        try {
            /**
             * Устанавливаем ключ конфига, который будем использовать
             */
            $telegram->setKey('water');
            $bot->setTelegram($telegram);
            $bot->handle($request);

            return response()->json(['success' => true]);

        } catch (TelegramException $e) {
            return $this->exceptionResponse($e);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e, 500);
        }
    }
}
