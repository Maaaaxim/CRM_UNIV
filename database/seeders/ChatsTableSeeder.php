<?php

namespace Database\Seeders;

use App\Models\PlatformUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Получение всех пользователей из таблицы platform_users
        $platformUsers = PlatformUser::all();

        // Для каждого пользователя создаем запись в таблице chats
        foreach ($platformUsers as $user) {
            DB::table('chats')->insert([
                'platform_user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
