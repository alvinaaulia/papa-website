<?php

namespace Database\Seeders;

use App\Models\Rules\SalaryComponent;
use Illuminate\Database\Seeder;

class ActiveSalaryComponentSeeder extends Seeder
{
    /**
     * Seed active salary components used by payroll rule engine.
     */
    public function run(): void
    {
        $components = [
            [
                'component_code' => 'OVERTIME_PAY',
                'component_name' => 'Upah Lembur',
                'component_type' => 'EARNING',
                'description' => 'Tambahan gaji dari total menit lembur.',
                'is_taxable' => true,
                'is_active' => true,
            ],
            [
                'component_code' => 'THR',
                'component_name' => 'THR',
                'component_type' => 'EARNING',
                'description' => 'Tunjangan Hari Raya dari komponen THR.',
                'is_taxable' => true,
                'is_active' => true,
            ],
            [
                'component_code' => 'TH_R',
                'component_name' => 'THR Legacy',
                'component_type' => 'EARNING',
                'description' => 'Komponen TH_R untuk kompatibilitas data lama.',
                'is_taxable' => true,
                'is_active' => true,
            ],
            [
                'component_code' => 'LATE_DEDUCTION',
                'component_name' => 'Potongan Keterlambatan',
                'component_type' => 'DEDUCTION',
                'description' => 'Potongan karena keterlambatan kehadiran.',
                'is_taxable' => false,
                'is_active' => true,
            ],
            [
                'component_code' => 'UNPAID_LEAVE_DEDUCTION',
                'component_name' => 'Potongan Cuti Unpaid',
                'component_type' => 'DEDUCTION',
                'description' => 'Potongan untuk cuti tanpa dibayar.',
                'is_taxable' => false,
                'is_active' => true,
            ],
            [
                'component_code' => 'TAX_FLAT',
                'component_name' => 'Potongan Pajak Flat',
                'component_type' => 'DEDUCTION',
                'description' => 'Potongan pajak flat dari tarif payroll.',
                'is_taxable' => false,
                'is_active' => true,
            ],
        ];

        foreach ($components as $component) {
            SalaryComponent::updateOrCreate(
                ['component_code' => $component['component_code']],
                $component
            );
        }
    }
}
