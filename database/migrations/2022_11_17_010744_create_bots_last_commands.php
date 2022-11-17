<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bots_commands_state', function (Blueprint $table) {
            $table->id();
            $table->string('bot')->comment('Ключ конфига/бот для которого сохраняем состояние');
            $table->bigInteger('chat_id')->comment('Ид чата');
            $table->string('command')->comment('Команда');
            $table->string('step')->nullable()->comment('Шаг команды');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bots_commands_state');
    }
};
