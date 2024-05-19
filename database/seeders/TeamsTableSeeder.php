<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teams')->insert([
            ['team' => 'Team A', 'desk_id' => 1],  // ссылаясь на ID деска
            ['team' => 'Team B', 'desk_id' => 2],
            // ... добавьте другие команды по аналогии
        ]);
    }
}
