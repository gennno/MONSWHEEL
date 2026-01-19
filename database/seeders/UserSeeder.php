<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'username' => 'admin',
                'name'     => 'System Admin',
                'email'    => 'admin@monswheel.local',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'status'   => 'active',
            ],
            [
                'username' => 'operator1',
                'name'     => 'Operator User',
                'email'    => 'operator1@monswheel.local',
                'password' => Hash::make('password'),
                'role'     => 'site',
                'status'   => 'active',
            ],
            [
                'username' => 'office1',
                'name'     => 'Office User 1',
                'email'    => 'office@monswheel.local',
                'password' => Hash::make('password'),
                'role'     => 'office',
                'status'   => 'active',
            ],
        ]);
    }
}
