<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramWebhookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message.text' => ['required', 'string'],
            'message.chat.id' => ['required', 'integer']
        ];
    }
}
