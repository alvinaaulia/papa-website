<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Direktur
        User::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Direktur Utama',
            'email' => 'direktur@mascitra.com',
            'password' => Hash::make('password123'),
            'role' => 'direktur'
        ]);

        // PSDM/HRD
        User::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Kepala PSDM',
            'email' => 'psdm@mascitra.com',
            'password' => Hash::make('password123'),
            'role' => 'PSDM'
        ]);

        // Project Manager
        User::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Manajer Proyek',
            'email' => 'pm@mascitra.com',
            'password' => Hash::make('password123'),
            'role' => 'project manager'
        ]);

        // Karyawan
        User::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Karyawan Contoh',
            'email' => 'karyawan@mascitra.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan'
        ]);
    }
}