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
        // создание таблицы retention_statuses
        Schema::create('retention_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        // обновление таблицы leads
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('status')->change();
            $table->unsignedBigInteger('retention_status')->change();

            $table->foreign('status')->references('id')->on('statuses');
            $table->foreign('retention_status')->references('id')->on('retention_statuses');
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
            $table->dropForeign(['status']);
            $table->dropForeign(['retention_status']);

            $table->bigInteger('status')->change();
            $table->bigInteger('retention_status')->change();
        });

        Schema::dropIfExists('retention_statuses');
    }
};
