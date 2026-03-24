<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\MasterLeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Seed leave data for every employee.
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

        $leaveTypes = MasterLeaveType::query()->pluck('leave_name')->all();
        if (empty($leaveTypes)) {
            $leaveTypes = [
                'Cuti Tahunan',
                'Cuti Sakit',
                'Cuti Keperluan Khusus',
            ];
        }

        $statusSamples = [
            ['approved', 'approved', 'approved'],
            ['approved', 'approved', 'pending'],
            ['approved', 'rejected', 'pending'],
            ['pending', 'pending', 'pending'],
        ];

        foreach ($employees as $employeeIndex => $employee) {
            for ($entry = 0; $entry < 2; $entry++) {
                $startDate = Carbon::now()
                    ->startOfMonth()
                    ->subMonths($entry + 1)
                    ->addDays((($employeeIndex + 1) * 3) + ($entry * 4));

                while ($startDate->isWeekend()) {
                    $startDate->addDay();
                }

                $durationDays = (($employeeIndex + $entry) % 3) + 1;
                $endDate = $startDate->copy()->addDays($durationDays - 1);

                while ($endDate->isWeekend()) {
                    $endDate->addDay();
                }

                $leaveType = $leaveTypes[($employeeIndex + $entry) % count($leaveTypes)];
                $statuses = $statusSamples[($employeeIndex + $entry) % count($statusSamples)];

                Leave::updateOrCreate(
                    [
                        'id_user' => $employee->id,
                        'leave_type' => $leaveType,
                        'start_of_leave' => $startDate->toDateString(),
                        'end_of_leave' => $endDate->toDateString(),
                    ],
                    [
                        'reason' => "Pengajuan {$leaveType} untuk {$employee->name}.",
                        'amount_of_leave' => ((int) $startDate->diffInDays($endDate)) + 1,
                        'notes' => '[Seeder] Data simulasi pengajuan cuti.',
                        'leave_address' => 'Alamat domisili karyawan',
                        'phone_number' => '081200000000',
                        'status_pm' => $statuses[0],
                        'status_hrd' => $statuses[1],
                        'status_director' => $statuses[2],
                    ]
                );
            }
        }
    }
}
