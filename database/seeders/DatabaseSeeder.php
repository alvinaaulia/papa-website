<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'role' => 'project manager',
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@company.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role' => 'project manager',
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@company.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role' => 'hrd',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@company.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role' => 'direktur',
                'name' => 'Dr. Maya Sari',
                'email' => 'maya.sari@company.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role' => 'karyawan',
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@company.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role' => 'karyawan',
                'name' => 'Dodi Pratama',
                'email' => 'dodi.pratama@company.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'role' => 'project manager',
                'name' => 'Fajar Setiawan',
                'email' => 'fajar.setiawan@company.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => $user['password'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->call([
            EmployeeSeeder::class,
            LeaveSeeder::class,
            PresenceSeeder::class,
            OvertimeSeeder::class,
            ActiveSalaryComponentSeeder::class,
            ActiveRuleSeeder::class,
            SalaryGradeSeeder::class,
            MasterSalarySeeder::class,
        ]);

    }
}
