<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\PlatformUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformUsersLeadIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = PlatformUser::all();

        foreach ($users as $user) {
            $lead = Lead::where('email', $user->email)->first();

            if ($lead) {
                $user->lead_id = $lead->id;
                $user->save();
            }
        }
    }
}
