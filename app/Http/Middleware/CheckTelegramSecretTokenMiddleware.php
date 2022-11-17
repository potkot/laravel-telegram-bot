<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTelegramSecretTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-Telegram-Bot-Api-Secret-Token') !== config('telegram.bot.water.request_secret_token')) {
            abort(403);
        }

        return $next($request);
    }
}
