<?php

namespace Database\Seeders;

use App\Models\Rules\Rule;
use App\Models\Rules\RuleVersion;
use App\Models\Rules\RuleVersionComponent;
use App\Models\Rules\SalaryComponent;
use Illuminate\Database\Seeder;

class ActiveRuleSeeder extends Seeder
{
    /**
     * Seed ACTIVE payroll rules and map each active version to an active component.
     */
    public function run(): void
    {
        $rules = [
            [
                'name' => 'Overtime Pay Rule',
                'component_code' => 'OVERTIME_PAY',
                'definition' => [
                    'conditions' => [
                        [
                            'field' => 'attendance.overtime_minutes',
                            'operator' => '>',
                            'value' => 0,
                        ],
                    ],
                    'action' => [
                        'type' => 'ADD_COMPONENT',
                        'code' => 'OVERTIME_PAY',
                        'formula' => 'attendance.overtime_minutes * rates.overtime_per_minute',
                    ],
                ],
            ],
            [
                'name' => 'Late Deduction Rule',
                'component_code' => 'LATE_DEDUCTION',
                'definition' => [
                    'conditions' => [
                        [
                            'field' => 'attendance.late_minutes',
                            'operator' => '>',
                            'value' => 0,
                        ],
                    ],
                    'action' => [
                        'type' => 'ADD_COMPONENT',
                        'code' => 'LATE_DEDUCTION',
                        'formula' => 'attendance.late_minutes * rates.late_deduction_per_minute',
                    ],
                ],
            ],
            [
                'name' => 'Unpaid Leave Deduction Rule',
                'component_code' => 'UNPAID_LEAVE_DEDUCTION',
                'definition' => [
                    'conditions' => [
                        [
                            'field' => 'attendance.unpaid_leave_days',
                            'operator' => '>',
                            'value' => 0,
                        ],
                    ],
                    'action' => [
                        'type' => 'ADD_COMPONENT',
                        'code' => 'UNPAID_LEAVE_DEDUCTION',
                        'formula' => 'attendance.unpaid_leave_days * rates.unpaid_leave_per_day',
                    ],
                ],
            ],
            [
                'name' => 'Tax Flat Deduction Rule',
                'component_code' => 'TAX_FLAT',
                'definition' => [
                    'conditions' => [
                        [
                            'field' => 'employee.status',
                            'operator' => '==',
                            'value' => 'active',
                        ],
                    ],
                    'action' => [
                        'type' => 'ADD_COMPONENT',
                        'code' => 'TAX_FLAT',
                        'formula' => 'rates.tax_flat_amount',
                    ],
                ],
            ],
            [
                'name' => 'THR Rule',
                'component_code' => 'THR',
                'definition' => [
                    'conditions' => [
                        [
                            'field' => 'components.THR',
                            'operator' => '>',
                            'value' => 0,
                        ],
                    ],
                    'action' => [
                        'type' => 'ADD_COMPONENT',
                        'code' => 'THR',
                        'formula' => 'components.THR',
                    ],
                ],
            ],
            [
                'name' => 'THR Legacy Rule',
                'component_code' => 'TH_R',
                'definition' => [
                    'conditions' => [
                        [
                            'field' => 'components.TH_R',
                            'operator' => '>',
                            'value' => 0,
                        ],
                    ],
                    'action' => [
                        'type' => 'ADD_COMPONENT',
                        'code' => 'TH_R',
                        'formula' => 'components.TH_R',
                    ],
                ],
            ],
        ];

        foreach ($rules as $ruleSeed) {
            $component = SalaryComponent::query()
                ->where('component_code', $ruleSeed['component_code'])
                ->where('is_active', true)
                ->first();

            if (!$component) {
                continue;
            }

            $rule = Rule::updateOrCreate(
                ['name' => $ruleSeed['name']],
                []
            );

            $activeVersion = RuleVersion::updateOrCreate(
                [
                    'rule_id' => $rule->id,
                    'version' => 1,
                ],
                [
                    'status' => RuleVersion::STATUS_ACTIVE,
                    'approval_status' => RuleVersion::APPROVAL_APPROVED,
                    'definition' => $ruleSeed['definition'],
                    'created_by' => null,
                    'approved_by' => null,
                    'approved_at' => now(),
                    'rejected_by' => null,
                    'rejected_at' => null,
                    'decision_notes' => 'Seeded as active payroll rule.',
                    'activated_at' => now(),
                ]
            );

            RuleVersion::query()
                ->where('rule_id', $rule->id)
                ->where('id', '!=', $activeVersion->id)
                ->where('status', RuleVersion::STATUS_ACTIVE)
                ->update(['status' => RuleVersion::STATUS_INACTIVE]);

            RuleVersionComponent::updateOrCreate(
                [
                    'rule_version_id' => $activeVersion->id,
                    'component_id' => $component->component_id,
                ],
                []
            );

            RuleVersionComponent::query()
                ->where('rule_version_id', $activeVersion->id)
                ->where('component_id', '!=', $component->component_id)
                ->delete();
        }
    }
}
