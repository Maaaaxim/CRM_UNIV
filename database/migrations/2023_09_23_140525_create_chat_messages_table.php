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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('sender_user_id')->nullable();
            $table->unsignedBigInteger('sender_platform_user_id')->nullable();
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();

            // Внешние ключи
            $table->foreign('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade');

            $table->foreign('sender_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('sender_platform_user_id')
                ->references('id')
                ->on('platform_users')
                ->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
