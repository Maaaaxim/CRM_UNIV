<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
//        Permission::create(['name' => 'create user']);
//        Permission::create(['name' => 'delete user']);
//        Permission::create(['name' => 'create lead']);
//        Permission::create(['name' => 'delete lead']);
//        Permission::create(['name' => 'create desk']);
//        Permission::create(['name' => 'delete desk']);
//        Permission::create(['name' => 'create country']);
//        Permission::create(['name' => 'delete country']);
//        Permission::create(['name' => 'create status']);
//        Permission::create(['name' => 'delete status']);
//        Permission::create(['name' => 'assign lead']);
//        Permission::create(['name' => 'import leads']);
//        Permission::create(['name' => 'view all leads']);
//        Permission::create(['name' => 'view all desks']);
//        Permission::create(['name' => 'view all statuses']);
//        Permission::create(['name' => 'view all countries']);
//        Permission::create(['name' => 'view all users']);
//        Permission::create(['name' => 'view all teams']);
//        Permission::create(['name' => 'create team']);
//        Permission::create(['name' => 'delete team']);
//        Permission::create(['name' => 'see online status']);
//        Permission::create(['name' => 'see online notification']);
//        Permission::create(['name' => 'actions with platform account']);
//        Permission::create(['name' => 'set permissions']);
//        Permission::create(['name' => 'editing user']);
//        Permission::create(['name' => 'change color']);
//        Permission::create(['name' => 'view dashboard']);
//        Permission::create(['name' => 'delete comments']);
        Permission::create(['name' => 'see users comments']);
    }
}
