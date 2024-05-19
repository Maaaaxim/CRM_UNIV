<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            1, 18, 19, 20, 21, 23, 24, 26, 27, 28, 29, 34, 35, 38, 41, 42, 43, 44, 45
        ];

        $retention_statuses = [
            1, 2, 3, 4, 6, 7, 8, 10, 11
        ];

        $countries = [
            4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 37, 38
        ];

        for ($i = 1; $i <= 150; $i++) {
            DB::table('leads')->insert([
                'user_id' => 140,
                'created_by' => 140,
                'name' => 'Test Lead ' . $i,
                'email' => 'test' . $i . '@example.com',
                'phone' => '123456789' . $i,
                'created_at' => now(),
                'updated_at' => now(),
                'note' => Str::random(50),
                'status' => $statuses[array_rand($statuses)],
                'country_id' => $countries[array_rand($countries)],
                'Affiliate' => 'Affiliate ' . $i,
                'Advert' => 'Advert ' . $i,
                'lead_value' => rand(100, 1000) / 10,
                'user_id_updated_at' => now(),
                'note_updated_at' => now(),
                'viewed' => rand(0, 1),
                'API' => rand(0, 1),
            ]);
        }
    }
}
