<?php

namespace Database\Seeders;

use App\Models\SalaryGrade;
use Illuminate\Database\Seeder;

class SalaryGradeSeeder extends Seeder
{
    /**
     * Seed salary grade master data.
     */
    public function run(): void
    {
        $grades = [
            [
                'grade_code' => 'TIER-A',
                'grade_name' => 'Junior',
                'min_score' => 0,
                'max_score' => 39,
                'base_salary' => 5000000,
                'status' => 'active',
                'description' => 'Entry level dengan kompetensi dasar sesuai posisi.',
            ],
            [
                'grade_code' => 'TIER-B',
                'grade_name' => 'Associate',
                'min_score' => 40,
                'max_score' => 59,
                'base_salary' => 7000000,
                'status' => 'active',
                'description' => 'Kompetensi menengah dengan pengalaman kerja yang cukup relevan.',
            ],
            [
                'grade_code' => 'TIER-C',
                'grade_name' => 'Senior',
                'min_score' => 60,
                'max_score' => 79,
                'base_salary' => 9500000,
                'status' => 'active',
                'description' => 'Kompetensi tinggi, pengalaman kuat, dan kontribusi signifikan.',
            ],
            [
                'grade_code' => 'TIER-D',
                'grade_name' => 'Lead/Expert',
                'min_score' => 80,
                'max_score' => 100,
                'base_salary' => 12500000,
                'status' => 'active',
                'description' => 'Level expert dengan kemampuan kepemimpinan dan strategis.',
            ],
        ];

        foreach ($grades as $grade) {
            SalaryGrade::updateOrCreate(
                ['grade_code' => $grade['grade_code']],
                $grade
            );
        }
    }
}

