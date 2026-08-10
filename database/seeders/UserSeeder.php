<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Administrator',
                'email'     => 'admin@stockify.com',
                'password'  => Hash::make('password'),
                'role'      => 'Admin',
            ],
            [
                'name'      => 'Budi Santoso',
                'email'     => 'manager@stockify.com',
                'password'  => Hash::make('password'),
                'role'      => 'Manajer Gudang',
            ],
            [
                'name'      => 'Siti Rahayu',
                'email'     => 'staff@stockify.com',
                'password'  => Hash::make('password'),
                'role'      => 'Staff Gudang',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
