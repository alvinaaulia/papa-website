<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MasterLeaveTypeSeeder::class,
        ]);
        $users = [
            [
                'id' => Str::uuid(),
                'role' => 'project manager',
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@company.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role' => 'project manager',
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@company.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role' => 'hrd',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@company.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role' => 'direktur',
                'name' => 'Dr. Maya Sari',
                'email' => 'maya.sari@company.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role' => 'karyawan',
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@company.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role' => 'karyawan',
                'name' => 'Dodi Pratama',
                'email' => 'dodi.pratama@company.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role' => 'project manager',
                'name' => 'Fajar Setiawan',
                'email' => 'fajar.setiawan@company.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

    }
}
