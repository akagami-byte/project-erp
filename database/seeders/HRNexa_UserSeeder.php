<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HRNexa_UserSeeder extends Seeder
{
    public function run()
    {
        // HR Manager
        User::firstOrCreate(
            ['email' => 'hr@nexaerp.com'],
            [
                'nama'       => 'Hendra Saputra',
                'nip'        => 'HR-2024-001',
                'email'      => 'hr@nexaerp.com',
                'password'   => Hash::make('password'),
                'role'       => 'hr',
                'departemen' => 'Human Resources',
                'divisi'     => 'HR Management',
            ]
        );

        // Karyawan 1
        User::firstOrCreate(
            ['email' => 'budi@nexaerp.com'],
            [
                'nama'       => 'Budi Santoso',
                'nip'        => 'KRY-2024-001',
                'email'      => 'budi@nexaerp.com',
                'password'   => Hash::make('password'),
                'role'       => 'karyawan',
                'departemen' => 'Teknologi Informasi',
                'divisi'     => 'Frontend Development',
            ]
        );

        // Karyawan 2
        User::firstOrCreate(
            ['email' => 'siti@nexaerp.com'],
            [
                'nama'       => 'Siti Rahayu',
                'nip'        => 'KRY-2024-002',
                'email'      => 'siti@nexaerp.com',
                'password'   => Hash::make('password'),
                'role'       => 'karyawan',
                'departemen' => 'Marketing',
                'divisi'     => 'Digital Marketing',
            ]
        );

        // Karyawan 3
        User::firstOrCreate(
            ['email' => 'ahmad@nexaerp.com'],
            [
                'nama'       => 'Ahmad Fauzi',
                'nip'        => 'KRY-2024-003',
                'email'      => 'ahmad@nexaerp.com',
                'password'   => Hash::make('password'),
                'role'       => 'karyawan',
                'departemen' => 'Finance',
                'divisi'     => 'Accounting',
            ]
        );

        // Karyawan 4
        User::firstOrCreate(
            ['email' => 'dewi@nexaerp.com'],
            [
                'nama'       => 'Dewi Kusuma',
                'nip'        => 'KRY-2024-004',
                'email'      => 'dewi@nexaerp.com',
                'password'   => Hash::make('password'),
                'role'       => 'karyawan',
                'departemen' => 'Operations',
                'divisi'     => 'Logistics',
            ]
        );
    }
}
