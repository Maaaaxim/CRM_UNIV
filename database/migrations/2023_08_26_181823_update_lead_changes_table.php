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
        Schema::table('lead_changes', function (Blueprint $table) {
            $table->string('old_value')->nullable()->change(); // изменение типа поля на string
            $table->string('new_value')->nullable()->change(); // изменение типа поля на string
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_changes', function (Blueprint $table) {
            $table->unsignedBigInteger('old_value')->nullable()->change(); // возвращение к первоначальному типу
            $table->unsignedBigInteger('new_value')->nullable()->change(); // возвращение к первоначальному типу
        });
    }
};
