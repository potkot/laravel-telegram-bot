<?php

namespace App\Providers;

use App\Services\Bot\Bot;
use App\Services\Bot\BotStateStore;
use App\Services\Telegram;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Telegram::class, function ($app) {
            return new Telegram(new Http());
        });

        $this->app->bind(Bot::class, function ($app) {
            return new Bot(new BotStateStore());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
