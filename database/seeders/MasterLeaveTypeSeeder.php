<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterLeaveType;

class MasterLeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            [
                'leave_name' => 'Cuti Tahunan',
                'description' => 'Hak cuti tahunan sesuai aturan perusahaan.'
            ],
            [
                'leave_name' => 'Cuti Sakit',
                'description' => 'Cuti karena alasan kesehatan dengan surat dokter.'
            ],
            [
                'leave_name' => 'Cuti Bersalin / Mendampingi Persalinan',
                'description' => 'Cuti melahirkan atau menemani istri melahirkan.'
            ],
            [
                'leave_name' => 'Cuti Keperluan Khusus',
                'description' => 'Cuti karena keperluan mendesak atau kebutuhan tertentu.'
            ],
            [
                'leave_name' => 'Cuti Tanpa Dibayar',
                'description' => 'Cuti yang diambil tanpa mendapatkan hak gaji.'
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            MasterLeaveType::firstOrCreate(
                ['leave_name' => $leaveType['leave_name']],
                $leaveType
            );
        }
    }
}
