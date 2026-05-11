<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama' => 'HR',
            'nip' => '12345678',
            'email' => 'HR@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'HR',
            'departemen' => 'HRD',
            'divisi' => 'Kepegawaian',
        ]);

        User::create([
            'nama' => 'Owner',
            'nip' => '87654321',
            'email' => 'Owner@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'Owner',
            'departemen' => 'Manajemen',
            'divisi' => 'Kepemilikan',
        ]);
    }
}