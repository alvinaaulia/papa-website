<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\MasterSalary;
use App\Models\Overtime;
use App\Models\Presence;
use App\Models\Rules\RuleVersion;
use App\Models\Rules\SalaryComponent;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class PayrollRuleEngineService
{
    /**
     * Execute ACTIVE payroll rules on Go rule-engine and return normalized breakdown.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(array $facts): array
    {
        $normalizedFacts = $this->normalizeFacts($facts);
        $definitions = $this->loadActiveRuleDefinitions();

        $actionCodes = collect($definitions)
            ->map(fn ($def) => strtoupper(trim((string) data_get($def, 'action.code', ''))))
            ->filter()
            ->unique()
            ->values();

        $componentMap = $this->loadActiveComponentMap($actionCodes);

        $missingCodes = $actionCodes
            ->reject(fn ($code) => $componentMap->has($code))
            ->values()
            ->all();

        if (!empty($missingCodes)) {
            throw ValidationException::withMessages([
                'rules' => [
                    'Terdapat action.code yang tidak terdaftar atau tidak aktif pada salary_components: ' .
                        implode(', ', $missingCodes),
                ],
            ]);
        }

        $payload = [
            'rules' => $definitions,
            'facts' => $normalizedFacts,
        ];

        $endpoint = rtrim((string) config('services.rule_engine.url', 'http://localhost:8081'), '/') . '/execute';
        $response = Http::timeout(15)->acceptJson()->post($endpoint, $payload);

        if (!$response->ok()) {
            throw new RuntimeException(
                'Rule engine Go gagal dipanggil (' . $response->status() . '): ' . $response->body()
            );
        }

        $engineResponse = $response->json();
        if (!is_array($engineResponse)) {
            throw new RuntimeException('Response rule engine Go tidak valid.');
        }

        $rawComponents = collect($engineResponse['components'] ?? []);
        $responseCodes = $rawComponents
            ->map(fn ($item) => strtoupper(trim((string) data_get($item, 'code', ''))))
            ->filter()
            ->unique()
            ->values();

        if ($responseCodes->isNotEmpty()) {
            $componentMap = $componentMap->merge($this->loadComponentMap($responseCodes));
        }

        $components = $rawComponents
            ->map(function ($component) use ($componentMap) {
                $code = strtoupper(trim((string) data_get($component, 'code', '')));
                $amount = (float) data_get($component, 'amount', 0);
                $sourceRule = (int) data_get($component, 'source_rule', 0);
                $mapped = $componentMap->get($code);

                return [
                    'code' => $code,
                    'name' => $mapped?->component_name ?? $code,
                    'type' => $mapped?->component_type ?? $this->guessTypeFromCode($code),
                    'amount' => round($amount, 2),
                    'source_rule' => $sourceRule,
                ];
            })
            ->values()
            ->all();

        $earnings = collect($components)
            ->where('type', 'EARNING')
            ->values()
            ->all();

        $deductions = collect($components)
            ->where('type', 'DEDUCTION')
            ->values()
            ->all();

        $summaryRaw = is_array($engineResponse['summary'] ?? null)
            ? $engineResponse['summary']
            : [];

        $basicSalary = (float) data_get($summaryRaw, 'basic_salary', data_get($normalizedFacts, 'employee.basic_salary', 0));
        $grossFromComponents = $basicSalary + (float) collect($earnings)->sum('amount');
        $deductionsFromComponents = (float) collect($deductions)->sum('amount');
        $netFromComponents = $grossFromComponents - $deductionsFromComponents;

        $grossSalary = !empty($components)
            ? $grossFromComponents
            : (float) data_get($summaryRaw, 'gross_salary', $grossFromComponents);
        $totalDeductions = !empty($components)
            ? $deductionsFromComponents
            : (float) data_get($summaryRaw, 'total_deductions', $deductionsFromComponents);
        $netSalary = !empty($components)
            ? $netFromComponents
            : (float) data_get($summaryRaw, 'net_salary', $netFromComponents);

        return [
            'facts' => $normalizedFacts,
            'payload' => $payload,
            'engine_response' => $engineResponse,
            'components' => $components,
            'earnings' => $earnings,
            'deductions' => $deductions,
            'summary' => [
                'basic_salary' => round($basicSalary, 2),
                'gross_salary' => round($grossSalary, 2),
                'total_deductions' => round($totalDeductions, 2),
                'net_salary' => round($netSalary, 2),
            ],
        ];
    }

    /**
     * Build payroll facts by reading attendance-related data from database
     * for the target payroll period.
     */
    public function buildFactsFromDatabase(MasterSalary $masterSalary, Carbon $salaryDate, array $overrides = []): array
    {
        [$periodStart, $periodEnd] = $this->resolvePayrollPeriod($masterSalary, $salaryDate);
        $attendanceFromDb = $this->collectAttendanceMetrics((string) $masterSalary->id_user, $periodStart, $periodEnd);

        $basicSalary = (float) ($masterSalary->salary_amount ?? 0);
        $defaultUnpaidPerDay = $basicSalary > 0 ? $basicSalary / 22 : 0;
        $defaultOvertimePerMinute = $basicSalary > 0 ? $basicSalary / (173 * 60) : 0;

        $manualAttendanceInput = [
            'days_present' => max((int) data_get($overrides, 'attendance.days_present', $attendanceFromDb['days_present']), 0),
            'days_absent' => max((int) data_get($overrides, 'attendance.days_absent', $attendanceFromDb['days_absent']), 0),
            'late_minutes' => max((int) data_get($overrides, 'attendance.late_minutes', $attendanceFromDb['late_minutes']), 0),
            'unpaid_leave_days' => max((int) data_get($overrides, 'attendance.unpaid_leave_days', $attendanceFromDb['unpaid_leave_days']), 0),
            'overtime_minutes' => max((int) data_get($overrides, 'attendance.overtime_minutes', $attendanceFromDb['overtime_minutes']), 0),
        ];
        $manualAttendanceInput['overtime_hours'] = intdiv($manualAttendanceInput['overtime_minutes'], 60);

        $hasManualAttendanceInput = collect(['late_minutes', 'unpaid_leave_days', 'overtime_minutes'])
            ->contains(fn ($key) => data_get($overrides, "attendance.{$key}") !== null);
        $preferManualAttendance = (bool) data_get($overrides, 'prefer_manual_attendance', false);

        $attendanceSource = 'database';
        $attendance = [
            'days_present' => $attendanceFromDb['days_present'],
            'days_absent' => $attendanceFromDb['days_absent'],
            'late_minutes' => $attendanceFromDb['late_minutes'],
            'unpaid_leave_days' => $attendanceFromDb['unpaid_leave_days'],
            'overtime_minutes' => $attendanceFromDb['overtime_minutes'],
            'overtime_hours' => $attendanceFromDb['overtime_hours'],
        ];

        if ($preferManualAttendance) {
            $attendance = $manualAttendanceInput;
            $attendanceSource = 'manual_override';
        } elseif (!$attendanceFromDb['has_records'] && $hasManualAttendanceInput) {
            $attendance = $manualAttendanceInput;
            $attendanceSource = 'manual_fallback';
        }

        $rates = [
            'late_deduction_per_minute' => max((float) data_get($overrides, 'rates.late_deduction_per_minute', 1000), 0),
            'unpaid_leave_per_day' => max((float) data_get($overrides, 'rates.unpaid_leave_per_day', $defaultUnpaidPerDay), 0),
            'overtime_per_minute' => max((float) data_get($overrides, 'rates.overtime_per_minute', $defaultOvertimePerMinute), 0),
            'tax_flat_amount' => max((float) data_get($overrides, 'rates.tax_flat_amount', $masterSalary->pph21 ?? 0), 0),
        ];
        $rates['overtime_per_hour'] = $rates['overtime_per_minute'] * 60;

        $components = [
            'BASIC_SALARY' => $basicSalary,
            'OVERTIME_PAY' => $attendance['overtime_minutes'] * $rates['overtime_per_minute'],
            'TH_R' => max((float) data_get($overrides, 'components.TH_R', data_get($overrides, 'components.th_r', 0)), 0),
            'THR' => max((float) data_get($overrides, 'components.THR', data_get($overrides, 'components.thr', 0)), 0),
        ];

        return [
            'employee' => [
                'status' => (string) data_get($overrides, 'employee.status', $masterSalary->status ?? 'active'),
                'contract_type' => (string) data_get($overrides, 'employee.contract_type', ''),
                'grade' => (string) data_get($overrides, 'employee.grade', $masterSalary->tier_grade ?? ''),
                'join_date' => (string) data_get($overrides, 'employee.join_date', ''),
                'years_of_service' => max((int) data_get($overrides, 'employee.years_of_service', 0), 0),
                'basic_salary' => $basicSalary,
            ],
            'attendance' => $attendance,
            'rates' => $rates,
            'components' => $components,
            'source' => [
                'type' => 'database',
                'attendance_source' => $attendanceSource,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'db_summary' => [
                    'presence_records' => $attendanceFromDb['presence_records'],
                    'approved_overtime_records' => $attendanceFromDb['approved_overtime_records'],
                    'approved_leave_records' => $attendanceFromDb['approved_leave_records'],
                    'period_workdays' => $attendanceFromDb['period_workdays'],
                ],
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function loadActiveRuleDefinitions(): array
    {
        $rules = RuleVersion::query()
            ->where('status', RuleVersion::STATUS_ACTIVE)
            ->orderBy('id')
            ->get();

        if ($rules->isEmpty()) {
            throw ValidationException::withMessages([
                'rules' => ['Belum ada rule ACTIVE. Aktifkan rule terlebih dahulu di repositori rules.'],
            ]);
        }

        return $rules
            ->map(function ($ruleVersion) {
                $definition = is_array($ruleVersion->definition) ? $ruleVersion->definition : [];

                if (isset($definition['action']['code']) && is_string($definition['action']['code'])) {
                    $definition['action']['code'] = strtoupper(trim($definition['action']['code']));
                }

                return $definition;
            })
            ->values()
            ->all();
    }

    private function loadActiveComponentMap(Collection $codes): Collection
    {
        if ($codes->isEmpty()) {
            return collect();
        }

        return SalaryComponent::query()
            ->whereIn('component_code', $codes->all())
            ->where('is_active', true)
            ->get()
            ->keyBy(fn ($item) => strtoupper(trim((string) $item->component_code)));
    }

    private function loadComponentMap(Collection $codes): Collection
    {
        if ($codes->isEmpty()) {
            return collect();
        }

        return SalaryComponent::query()
            ->whereIn('component_code', $codes->all())
            ->get()
            ->keyBy(fn ($item) => strtoupper(trim((string) $item->component_code)));
    }

    private function guessTypeFromCode(string $code): string
    {
        $upper = strtoupper($code);
        $deductionKeywords = ['DEDUCTION', 'POTONG', 'TAX', 'PPH', 'BPJS', 'DENDA', 'PINJAMAN'];

        foreach ($deductionKeywords as $keyword) {
            if (str_contains($upper, $keyword)) {
                return 'DEDUCTION';
            }
        }

        return 'EARNING';
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolvePayrollPeriod(MasterSalary $masterSalary, Carbon $salaryDate): array
    {
        $periodStart = $masterSalary->period_start
            ? Carbon::parse($masterSalary->period_start)->startOfDay()
            : $salaryDate->copy()->startOfMonth()->startOfDay();

        $periodEnd = $masterSalary->period_end
            ? Carbon::parse($masterSalary->period_end)->endOfDay()
            : $salaryDate->copy()->endOfMonth()->endOfDay();

        if ($periodEnd->lt($periodStart)) {
            $periodStart = $salaryDate->copy()->startOfMonth()->startOfDay();
            $periodEnd = $salaryDate->copy()->endOfMonth()->endOfDay();
        }

        return [$periodStart, $periodEnd];
    }

    /**
     * @return array{
     *     days_present:int,
     *     days_absent:int,
     *     late_minutes:int,
     *     unpaid_leave_days:int,
     *     overtime_minutes:int,
     *     overtime_hours:int,
     *     presence_records:int,
     *     approved_overtime_records:int,
     *     approved_leave_records:int,
     *     period_workdays:int,
     *     has_records:bool
     * }
     */
    private function collectAttendanceMetrics(string $userId, Carbon $periodStart, Carbon $periodEnd): array
    {
        $periodStartDate = $periodStart->toDateString();
        $periodEndDate = $periodEnd->toDateString();

        $presences = Presence::query()
            ->where('id_user', $userId)
            ->whereBetween('date', [$periodStartDate, $periodEndDate])
            ->get(['check_in']);

        $daysPresent = $presences
            ->filter(fn ($presence) => !empty($presence->check_in))
            ->count();

        $lateMinutes = $this->calculateLateMinutes(
            $presences->pluck('check_in')->filter()->values()->all()
        );

        $approvedOvertimes = Overtime::query()
            ->where('id_user', $userId)
            ->where('status', 'approved')
            ->whereBetween('date', [$periodStartDate, $periodEndDate])
            ->get(['total_overtime']);

        $overtimeMinutes = (int) $approvedOvertimes->sum('total_overtime');

        $leaveMetrics = $this->calculateApprovedLeaveMetrics($userId, $periodStart, $periodEnd);
        $periodWorkdays = $this->countWeekdaysInRange($periodStart, $periodEnd);
        $daysAbsent = max($periodWorkdays - $daysPresent - $leaveMetrics['approved_leave_days'], 0);

        return [
            'days_present' => $daysPresent,
            'days_absent' => $daysAbsent,
            'late_minutes' => $lateMinutes,
            'unpaid_leave_days' => $leaveMetrics['unpaid_leave_days'],
            'overtime_minutes' => $overtimeMinutes,
            'overtime_hours' => intdiv($overtimeMinutes, 60),
            'presence_records' => $presences->count(),
            'approved_overtime_records' => $approvedOvertimes->count(),
            'approved_leave_records' => $leaveMetrics['approved_leave_records'],
            'period_workdays' => $periodWorkdays,
            'has_records' => $presences->isNotEmpty()
                || $approvedOvertimes->isNotEmpty()
                || $leaveMetrics['approved_leave_records'] > 0,
        ];
    }

    private function calculateLateMinutes(array $checkInTimes): int
    {
        $standardStartMinutes = 8 * 60;
        $totalLateMinutes = 0;

        foreach ($checkInTimes as $checkInTime) {
            $checkInMinutes = $this->timeToMinutes($checkInTime);
            if ($checkInMinutes !== null && $checkInMinutes > $standardStartMinutes) {
                $totalLateMinutes += ($checkInMinutes - $standardStartMinutes);
            }
        }

        return $totalLateMinutes;
    }

    /**
     * @return array{approved_leave_days:int, unpaid_leave_days:int, approved_leave_records:int}
     */
    private function calculateApprovedLeaveMetrics(string $userId, Carbon $periodStart, Carbon $periodEnd): array
    {
        $periodStartDate = $periodStart->toDateString();
        $periodEndDate = $periodEnd->toDateString();

        $leaves = Leave::query()
            ->where('id_user', $userId)
            ->where('status_pm', 'approved')
            ->where('status_hrd', 'approved')
            ->where('status_director', 'approved')
            ->where(function ($query) use ($periodStartDate, $periodEndDate) {
                $query->whereBetween('start_of_leave', [$periodStartDate, $periodEndDate])
                    ->orWhereBetween('end_of_leave', [$periodStartDate, $periodEndDate])
                    ->orWhere(function ($nested) use ($periodStartDate, $periodEndDate) {
                        $nested->where('start_of_leave', '<=', $periodStartDate)
                            ->where('end_of_leave', '>=', $periodEndDate);
                    });
            })
            ->get(['leave_type', 'start_of_leave', 'end_of_leave']);

        $approvedLeaveDays = 0;
        $unpaidLeaveDays = 0;

        foreach ($leaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_of_leave)->startOfDay();
            $leaveEnd = Carbon::parse($leave->end_of_leave)->endOfDay();
            $overlapDays = $this->calculateOverlapDays($leaveStart, $leaveEnd, $periodStart, $periodEnd);

            if ($overlapDays <= 0) {
                continue;
            }

            $approvedLeaveDays += $overlapDays;

            if ($this->isUnpaidLeaveType((string) $leave->leave_type)) {
                $unpaidLeaveDays += $overlapDays;
            }
        }

        return [
            'approved_leave_days' => $approvedLeaveDays,
            'unpaid_leave_days' => $unpaidLeaveDays,
            'approved_leave_records' => $leaves->count(),
        ];
    }

    private function isUnpaidLeaveType(string $leaveType): bool
    {
        $normalized = strtolower(trim($leaveType));

        return str_contains($normalized, 'tanpa dibayar')
            || str_contains($normalized, 'unpaid');
    }

    private function calculateOverlapDays(
        Carbon $rangeStart,
        Carbon $rangeEnd,
        Carbon $periodStart,
        Carbon $periodEnd
    ): int {
        $start = $rangeStart->greaterThan($periodStart) ? $rangeStart->copy() : $periodStart->copy();
        $end = $rangeEnd->lessThan($periodEnd) ? $rangeEnd->copy() : $periodEnd->copy();

        if ($end->lt($start)) {
            return 0;
        }

        return (int) $start->diffInDays($end) + 1;
    }

    private function countWeekdaysInRange(Carbon $periodStart, Carbon $periodEnd): int
    {
        $cursor = $periodStart->copy()->startOfDay();
        $end = $periodEnd->copy()->startOfDay();
        $total = 0;

        while ($cursor->lte($end)) {
            if ($cursor->isWeekday()) {
                $total++;
            }
            $cursor->addDay();
        }

        return $total;
    }

    private function timeToMinutes(mixed $time): ?int
    {
        if (!is_string($time) || trim($time) === '') {
            return null;
        }

        if (preg_match('/^(\d{1,2}):(\d{2})(?::\d{2})?$/', trim($time), $matches) === 1) {
            $hours = (int) $matches[1];
            $minutes = (int) $matches[2];

            if ($hours >= 0 && $hours <= 23 && $minutes >= 0 && $minutes <= 59) {
                return ($hours * 60) + $minutes;
            }
        }

        try {
            $parsed = Carbon::parse($time);
            return (((int) $parsed->format('H')) * 60) + (int) $parsed->format('i');
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeFacts(array $facts): array
    {
        $basicSalary = (int) round((float) data_get($facts, 'employee.basic_salary', 0));
        $componentFacts = data_get($facts, 'components', []);
        $overtimeMinutes = max((int) data_get($facts, 'attendance.overtime_minutes', 0), 0);
        $overtimePerMinute = max((int) round((float) data_get($facts, 'rates.overtime_per_minute', 0)), 0);
        $normalizedComponents = [
            'BASIC_SALARY' => max($basicSalary, 0),
        ];

        if (is_array($componentFacts)) {
            foreach ($componentFacts as $key => $value) {
                $normalizedComponents[strtoupper((string) $key)] = max((int) round((float) $value), 0);
            }
        }

        return [
            'employee' => [
                'status' => (string) data_get($facts, 'employee.status', 'active'),
                'contract_type' => (string) data_get($facts, 'employee.contract_type', ''),
                'grade' => (string) data_get($facts, 'employee.grade', ''),
                'join_date' => (string) data_get($facts, 'employee.join_date', ''),
                'years_of_service' => (int) data_get($facts, 'employee.years_of_service', 0),
                'basic_salary' => max($basicSalary, 0),
            ],
            'attendance' => [
                'days_present' => max((int) data_get($facts, 'attendance.days_present', 0), 0),
                'days_absent' => max((int) data_get($facts, 'attendance.days_absent', 0), 0),
                'late_minutes' => max((int) data_get($facts, 'attendance.late_minutes', 0), 0),
                'unpaid_leave_days' => max((int) data_get($facts, 'attendance.unpaid_leave_days', 0), 0),
                'overtime_hours' => max((int) data_get($facts, 'attendance.overtime_hours', intdiv($overtimeMinutes, 60)), 0),
                'overtime_minutes' => $overtimeMinutes,
            ],
            'rates' => [
                'late_deduction_per_minute' => max((int) round((float) data_get($facts, 'rates.late_deduction_per_minute', 0)), 0),
                'unpaid_leave_per_day' => max((int) round((float) data_get($facts, 'rates.unpaid_leave_per_day', 0)), 0),
                'overtime_per_hour' => max((int) round((float) data_get($facts, 'rates.overtime_per_hour', $overtimePerMinute * 60)), 0),
                'overtime_per_minute' => $overtimePerMinute,
                'tax_flat_amount' => max((int) round((float) data_get($facts, 'rates.tax_flat_amount', 0)), 0),
            ],
            'components' => $normalizedComponents,
        ];
    }
}
