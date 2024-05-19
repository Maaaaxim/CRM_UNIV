<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('desks')->insert([
            ['desk' => 'Desk 1'],
            ['desk' => 'Desk 2'],
            // ... добавьте другие дески по аналогии
        ]);
    }
}
