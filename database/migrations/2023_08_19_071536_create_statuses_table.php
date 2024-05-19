<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор статуса
            $table->string('name')->unique(); // Название статуса, должно быть уникальным
            $table->timestamps(); // временные метки создания/изменения
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses'); // Удаление таблицы при откате миграции
    }
};
