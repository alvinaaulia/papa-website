<?php

namespace Database\Seeders;

use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresenceSeeder extends Seeder
{
    /**
     * Seed attendance data (absensi) for every employee.
     */
    public function run(): void
    {
        $employees = User::query()
            ->where('role', 'karyawan')
            ->orderBy('name')
            ->get(['id']);

        if ($employees->isEmpty()) {
            return;
        }

        $attendanceDates = $this->buildRecentWorkingDates(12);

        foreach ($employees as $employeeIndex => $employee) {
            foreach ($attendanceDates as $dateIndex => $date) {
                $checkInMinutes = (8 * 60) + ((($employeeIndex * 11) + ($dateIndex * 7)) % 41) - 10;
                $checkOutMinutes = (17 * 60) + ((($employeeIndex * 13) + ($dateIndex * 5)) % 76);

                if ($checkOutMinutes <= $checkInMinutes) {
                    $checkOutMinutes = $checkInMinutes + 510;
                }

                $notCheckedOut = (($employeeIndex + $dateIndex) % 17) === 0;

                Presence::updateOrCreate(
                    [
                        'id_user' => $employee->id,
                        'date' => $date->toDateString(),
                    ],
                    [
                        'check_in' => $this->minutesToTime($checkInMinutes),
                        'check_out' => $notCheckedOut ? null : $this->minutesToTime($checkOutMinutes),
                        'work_time' => $notCheckedOut ? null : ($checkOutMinutes - $checkInMinutes),
                        'in_photo' => null,
                        'out_photo' => null,
                    ]
                );
            }
        }
    }

    /**
     * Build list of recent weekdays.
     *
     * @return array<int, Carbon>
     */
    private function buildRecentWorkingDates(int $totalDays): array
    {
        $dates = [];
        $cursor = Carbon::today();

        while (count($dates) < $totalDays) {
            if ($cursor->isWeekday()) {
                $dates[] = $cursor->copy();
            }

            $cursor->subDay();
        }

        return array_reverse($dates);
    }

    private function minutesToTime(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return sprintf('%02d:%02d:00', $hours, $mins);
    }
}
