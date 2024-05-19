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
        Schema::table('leads', function (Blueprint $table) {
            $table->text('note')->nullable(); // Добавляем поле note типа TEXT, которое может быть NULL
            $table->string('status')->nullable(); // Добавляем поле status типа STRING, которое также может быть NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['note', 'status']); // Удаляем поля note и status при откате миграции
        });
    }
};
