<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('CASCADE');
        });

        Schema::create('waters_bases', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->uuid('region_uuid');
            $table->timestamps();

            $table->foreign('region_uuid')->references('uuid')->on('regions')->onDelete('CASCADE');
        });

        Schema::create('waters_volumes', function (Blueprint $table) {
            $table->id();
            $table->uuid('water_base_uuid');
            $table->bigInteger('volume');
            $table->timestamps();

            $table->foreign('water_base_uuid')->references('uuid')->on('waters_bases')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('waters_volumes');
        Schema::dropIfExists('waters_bases');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('regions');

    }
};
