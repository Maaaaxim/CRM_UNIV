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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('desk_id')->nullable()->after('role');
            $table->unsignedBigInteger('team_id')->nullable()->after('desk_id');

            $table->foreign('desk_id')
                ->references('desk_id')
                ->on('desks')
                ->onDelete('set null');

            $table->foreign('team_id')
                ->references('team_id')
                ->on('teams')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
