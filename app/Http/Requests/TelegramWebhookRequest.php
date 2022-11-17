<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramWebhookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => ['required_without:callback_query'],
            'message.text' => ['required_with:message', 'string'],
            'message.chat.id' => ['required_with:message', 'integer'],
            'callback_query' => ['required_without:message'],
            'callback_query.from.id' => ['required_with:callback_query'],
            'callback_query.data' => ['required_with:callback_query']
        ];
    }

    protected function passedValidation()
    {
        if ($this->has('callback_query')) {
            $this->merge([
                'text' => $this->input('callback_query.data'),
                'chat_id' => $this->input('callback_query.from.id'),
            ]);
        } else {
            $this->merge([
                'text' => $this->input('message.text'),
                'chat_id' => $this->input('message.chat.id'),
            ]);
        }

    }
}
