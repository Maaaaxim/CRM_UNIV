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
        Schema::create('lead_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');    // Кто добавил комментарий
            $table->unsignedBigInteger('lead_id');    // Какому лиду добавлен комментарий
            $table->text('body');                     // Тело комментария
            $table->timestamps();                     // created_at будет временем добавления комментария

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
        Schema::dropIfExists('lead_comments');
    }
};
