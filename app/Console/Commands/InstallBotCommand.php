<?php

namespace App\Console\Commands;

use App\Exceptions\TelegramException;
use App\Services\Telegram;
use Illuminate\Console\Command;

class InstallBotCommand extends Command
{
    protected $signature = 'install-bot {--without-webhook} {--without-commands}';

    protected $description = 'Will be set webhook and commands for bot with «water» config';

    public function handle(Telegram $telegram)
    {
        try {
            $telegram->setKey('water');

            $withoutWebhook = $this->option('without-webhook');
            $withoutCommands = $this->option('without-commands');

            if (!$withoutWebhook) {
                $this->setWebhook($telegram);
            }

            if (!$withoutCommands) {
                $this->setMyCommands($telegram);
            }

        } catch (TelegramException $e) {
            $this->warn($e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Устанавливаем webhook для telegram
     *
     * @param Telegram $telegram
     * @return void
     */
    private function setWebhook(Telegram $telegram)
    {
        $urlWebhook = route('telegram-handler-webhook');
        $secretToken = config('telegram.bot.water.request_secret_token');

        $response = $telegram->setWebhook($urlWebhook, $secretToken);

        if ($response === false) {
            $this->error(__('Webhook dont be set'));
            $this->warn($telegram->response);
        } else {
            $this->info(__('Webhook set success'));
        }
    }

    /**
     * Добавляем команды в подсказку бота
     *
     * @param Telegram $telegram
     * @return void
     */
    private function setMyCommands(Telegram $telegram)
    {
        $response = $telegram->setMyCommands();
        if ($response === false) {
            $this->error(__('Commands dont be set'));
            $this->warn($telegram->response);
        } else {
            $this->info(__('Commands set success'));
        }
    }
}
