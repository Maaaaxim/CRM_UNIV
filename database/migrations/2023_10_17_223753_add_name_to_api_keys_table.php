<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->string('name')->after('id'); // Добавление поля name после id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropColumn('name'); // Удаление поля name при откате миграции
        });
    }
};
