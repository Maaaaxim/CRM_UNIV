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
            $table->unsignedBigInteger('created_by')->nullable()->after('user_id'); // Используем nullable(), если это поле может быть пустым
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null'); // Добавляем внешний ключ для связи с таблицей users
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
            $table->dropForeign(['created_by']); // Удаляем внешний ключ
            $table->dropColumn('created_by'); // Удаляем столбец
        });
    }
};
