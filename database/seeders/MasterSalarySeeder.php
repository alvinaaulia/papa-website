<?php

namespace Database\Seeders;

use App\Models\MasterSalary;
use App\Models\SalaryGrade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MasterSalarySeeder extends Seeder
{
    /**
     * Seed master salary data for existing employees.
     */
    public function run(): void
    {
        $employees = User::query()
            ->where('role', 'karyawan')
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($employees->isEmpty()) {
            return;
        }

        $activeGrades = SalaryGrade::query()
            ->where('status', 'active')
            ->orderBy('min_score')
            ->get();

        if ($activeGrades->isEmpty()) {
            return;
        }

        $scoreSamples = [88, 76, 65, 57, 44, 38];
        $periodStart = Carbon::now()->startOfMonth()->toDateString();
        $periodEnd = Carbon::now()->endOfMonth()->toDateString();

        foreach ($employees as $index => $employee) {
            $score = $scoreSamples[$index % count($scoreSamples)];
            $grade = $activeGrades->first(function ($item) use ($score) {
                return $score >= (int) $item->min_score && $score <= (int) $item->max_score;
            }) ?? $activeGrades->last();

            if (!$grade) {
                continue;
            }

            $salaryAmount = (float) $grade->base_salary + (($index % 3) * 250000);
            $pph21 = $this->calculatePph21($salaryAmount);
            $netSalary = $salaryAmount - $pph21;

            MasterSalary::updateOrCreate(
                [
                    'id_user' => $employee->id,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                ],
                [
                    'salary_amount' => $salaryAmount,
                    'pph21' => $pph21,
                    'net_salary' => $netSalary,
                    'tier_grade' => $grade->grade_code,
                    'evaluation_score' => $score,
                    'assessment_notes' => $this->buildAssessmentNote($employee->name, $score, $grade->grade_code),
                    'status' => 'active',
                ]
            );
        }
    }

    /**
     * Calculate monthly PPh21 using the same progressive rules as controller logic.
     */
    private function calculatePph21(float $salaryAmount): float
    {
        $yearlySalary = $salaryAmount * 12;
        $ptkp = 54000000;
        $pkp = max(0, $yearlySalary - $ptkp);
        $pph21Yearly = 0;

        if ($pkp <= 60000000) {
            $pph21Yearly = $pkp * 0.05;
        } elseif ($pkp <= 250000000) {
            $pph21Yearly = (60000000 * 0.05) + (($pkp - 60000000) * 0.15);
        } elseif ($pkp <= 500000000) {
            $pph21Yearly = (60000000 * 0.05) + (190000000 * 0.15) + (($pkp - 250000000) * 0.25);
        } else {
            $pph21Yearly = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + (($pkp - 500000000) * 0.30);
        }

        return round($pph21Yearly / 12, 2);
    }

    /**
     * Build sample notes for CV-based salary evaluation.
     */
    private function buildAssessmentNote(string $employeeName, int $score, string $gradeCode): string
    {
        return "[Seeder CV] Penilaian untuk {$employeeName}: skor akhir {$score}/100, tier {$gradeCode}. Data dihasilkan untuk kebutuhan demo pengaturan gaji.";
    }
}

