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
        Schema::create('lead_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id'); // ID лида
            $table->string('change_type'); // Тип изменения
            $table->unsignedBigInteger('changed_by'); // ID пользователя, который внес изменения
            $table->unsignedBigInteger('old_value')->nullable(); // Прежнее значение (например, старый user_id)
            $table->unsignedBigInteger('new_value')->nullable(); // Новое значение (например, новый user_id)
            $table->timestamp('change_date')->useCurrent(); // Дата изменения
            $table->timestamps();

            // Внешние ключи
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_changes');
    }
};
