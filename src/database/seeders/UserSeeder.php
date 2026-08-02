<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => '一般ユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => false,
        ]);

        User::create([
            'name' => '一般ユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => false,
        ]);

        User::create([
            'name' => '管理者ユーザー',
            'email' => 'user3@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);
    }
}