<?php

namespace Database\Seeders;

use App\Models\Overtime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OvertimeSeeder extends Seeder
{
    /**
     * Seed overtime data for every employee.
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

        $statusSamples = ['approved', 'pending', 'rejected'];

        foreach ($employees as $employeeIndex => $employee) {
            for ($entry = 0; $entry < 2; $entry++) {
                $date = Carbon::today()->subWeekdays(($employeeIndex * 2) + $entry + 1);

                $startMinutes = (18 * 60) + ((($employeeIndex * 9) + ($entry * 15)) % 46);
                $durationMinutes = 90 + ((($employeeIndex * 21) + ($entry * 17)) % 91);
                $endMinutes = $startMinutes + $durationMinutes;

                if ($endMinutes >= (24 * 60)) {
                    $endMinutes = (24 * 60) - 1;
                    $durationMinutes = $endMinutes - $startMinutes;
                }

                Overtime::updateOrCreate(
                    [
                        'id_user' => $employee->id,
                        'date' => $date->toDateString(),
                        'start_overtime' => $this->minutesToTime($startMinutes),
                    ],
                    [
                        'end_overtime' => $this->minutesToTime($endMinutes),
                        'total_overtime' => $durationMinutes,
                        'description' => "Lembur {$employee->name} untuk penyelesaian tugas proyek.",
                        'proof' => null,
                        'status' => $statusSamples[($employeeIndex + $entry) % count($statusSamples)],
                    ]
                );
            }
        }
    }

    private function minutesToTime(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return sprintf('%02d:%02d:00', $hours, $mins);
    }
}
