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
        Schema::table('notifications', function (Blueprint $table) {
            // Сначала удалите старый внешний ключ
            $table->dropForeign(['lead_id']);

            // Теперь добавьте новый внешний ключ с ON DELETE CASCADE
            $table->foreign('lead_id')
                ->references('id')->on('leads')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // При откате миграции сначала удалите измененный внешний ключ
            $table->dropForeign(['lead_id']);

            // Теперь добавьте оригинальный внешний ключ без каскадного удаления
            $table->foreign('lead_id')
                ->references('id')->on('leads');
        });
    }
};
