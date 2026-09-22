<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Administrator (default, hidden from UI)
        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name'      => 'Super Administrator',
                'username'  => 'superadmin',
                'password'  => Hash::make('12345678'),
                'role'      => 'superadministrator',
                'is_active' => true,
            ]
        );

        // Default Administrator
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'      => 'Administrator',
                'username'  => 'admin',
                'password'  => Hash::make('12345678'),
                'role'      => 'administrator',
                'is_active' => true,
            ]
        );

        // Default User
        User::updateOrCreate(
            ['username' => 'user'],
            [
                'name'      => 'User Biasa',
                'username'  => 'user',
                'password'  => Hash::make('12345678'),
                'role'      => 'user',
                'is_active' => true,
            ]
        );
    }
}
