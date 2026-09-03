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
                'name' => 'Ashari Surya Putra',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'no_hp' => '081234567890',
                'alamat' => 'Bandung, West Java',
            ],
            [
                'name' => 'Ashari Surya Putra',
                'email' => 'petugas@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
                'no_hp' => '082345678901',
                'alamat' => 'Baleendah, Bandung',
            ],
            [
                'name' => 'Ashari Surya Putra',
                'email' => 'ashari@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'no_hp' => '083456789012',
                'alamat' => 'Ciparay, Bandung',
            ],
            [
                'name' => 'Ashari Surya Putra',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'no_hp' => '084567890123',
                'alamat' => 'Dayeuhkolot, Bandung',
            ],
            [
                'name' => 'Ashari Surya Putra',
                'email' => 'azhar@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'peminjam',
                'no_hp' => '085678901234',
                'alamat' => 'Banjaran, Bandung',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}