<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use LaravelUac\Database\UacMenu;
use LaravelUac\Database\UacRole;

class LaravelUacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $userModel = config('auth.providers.users.model');
        $user = $userModel::create([
            'username' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@mail',
            'password' => Hash::make('admin'),
        ]);

        $menu = UacMenu::create([
            'parent_id' => 0,
            'order' => 0,
            'title' => 'Home',
            'icon' => 'fas fa-home',
            'uri' => '/home',
        ]);

        $role = UacRole::create([
            'slug' => 'admin',
            'name' => 'Admin',
        ]);

        DB::table('uac_role_menu')->insert([
            'role_id' => $role->id,
            'menu_id' => $menu->id,
        ]);

        DB::table('uac_role_users')->insert([
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);
    }
}
