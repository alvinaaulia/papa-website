<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed employee users and their profiles.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@company.com',
                'nip' => 'EMP-0001',
                'type_of_employment' => 'offline',
                'gender' => 'female',
                'address' => 'Jl. Melati No. 12',
                'sub_district' => 'Sukolilo',
                'province' => 'Jawa Timur',
                'birth_place' => 'Surabaya',
                'date_of_birth' => '1998-04-12',
                'nik' => '3578011204980001',
                'phone_number' => '081234567801',
            ],
            [
                'name' => 'Dodi Pratama',
                'email' => 'dodi.pratama@company.com',
                'nip' => 'EMP-0002',
                'type_of_employment' => 'online',
                'gender' => 'male',
                'address' => 'Jl. Kenanga No. 8',
                'sub_district' => 'Lowokwaru',
                'province' => 'Jawa Timur',
                'birth_place' => 'Malang',
                'date_of_birth' => '1996-10-03',
                'nik' => '3573010310960002',
                'phone_number' => '081234567802',
            ],
            [
                'name' => 'Anita Lestari',
                'email' => 'anita.lestari@company.com',
                'nip' => 'EMP-0003',
                'type_of_employment' => 'offline',
                'gender' => 'female',
                'address' => 'Jl. Cempaka No. 21',
                'sub_district' => 'Klojen',
                'province' => 'Jawa Timur',
                'birth_place' => 'Kediri',
                'date_of_birth' => '1999-01-24',
                'nik' => '3506012401990003',
                'phone_number' => '081234567803',
            ],
            [
                'name' => 'Bagas Saputra',
                'email' => 'bagas.saputra@company.com',
                'nip' => 'EMP-0004',
                'type_of_employment' => 'freelance',
                'gender' => 'male',
                'address' => 'Jl. Anggrek No. 5',
                'sub_district' => 'Blimbing',
                'province' => 'Jawa Timur',
                'birth_place' => 'Blitar',
                'date_of_birth' => '1997-07-15',
                'nik' => '3505021507970004',
                'phone_number' => '081234567804',
            ],
            [
                'name' => 'Nadia Putri',
                'email' => 'nadia.putri@company.com',
                'nip' => 'EMP-0005',
                'type_of_employment' => 'online',
                'gender' => 'female',
                'address' => 'Jl. Mawar No. 3',
                'sub_district' => 'Tegalsari',
                'province' => 'Jawa Timur',
                'birth_place' => 'Madiun',
                'date_of_birth' => '2000-12-09',
                'nik' => '3577090912000005',
                'phone_number' => '081234567805',
            ],
        ];

        foreach ($employees as $employee) {
            $user = User::updateOrCreate(
                ['email' => $employee['email']],
                [
                    'name' => $employee['name'],
                    'role' => 'karyawan',
                    'password' => Hash::make('12345678'),
                ]
            );

            Profile::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'name' => $employee['name'],
                    'nip' => $employee['nip'],
                    'type_of_employment' => $employee['type_of_employment'],
                    'gender' => $employee['gender'],
                    'address' => $employee['address'],
                    'sub_district' => $employee['sub_district'],
                    'province' => $employee['province'],
                    'birth_place' => $employee['birth_place'],
                    'date_of_birth' => $employee['date_of_birth'],
                    'nik' => $employee['nik'],
                    'phone_number' => $employee['phone_number'],
                ]
            );
        }
    }
}
