<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\MasterSalary;
use App\Models\Salary;
use App\Services\PayrollRuleEngineService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SalaryController extends Controller
{
    public function __construct(
        private PayrollRuleEngineService $payrollRuleEngineService
    ) {
    }

    public function getPendingPayments(Request $request)
    {
        try {
            $targetDate = $request->input('salary_date', now()->toDateString());
            $date = Carbon::parse($targetDate);

            $pendingSalaries = MasterSalary::with('user:id,name')
                ->where('status', 'active')
                ->whereHas('user')
                ->whereDoesntHave('salaries', function ($query) use ($date) {
                    $query->whereYear('salary_date', $date->year)
                        ->whereMonth('salary_date', $date->month);
                })
                ->orderByDesc('updated_at')
                ->get()
                ->map(function ($masterSalary) use ($date) {
                    $basicSalary = (float) ($masterSalary->salary_amount ?? 0);
                    $defaultLateRate = 1000;
                    $defaultUnpaidPerDay = $basicSalary > 0 ? round($basicSalary / 22) : 0;
                    $defaultOvertimePerMinute = $basicSalary > 0 ? round($basicSalary / (173 * 60)) : 0;

                    return [
                        'id_master_salary' => $masterSalary->id_master_salary,
                        'employee_name' => $masterSalary->user->name ?? 'N/A',
                        'salary_amount' => $basicSalary,
                        'pph21' => (float) ($masterSalary->pph21 ?? 0),
                        'net_salary' => (float) ($masterSalary->net_salary ?? $basicSalary),
                        'status' => $masterSalary->status,
                        'salary_date' => $date->toDateString(),
                        'default_rates' => [
                            'late_deduction_per_minute' => $defaultLateRate,
                            'unpaid_leave_per_day' => $defaultUnpaidPerDay,
                            'overtime_per_minute' => $defaultOvertimePerMinute,
                            'tax_flat_amount' => (float) ($masterSalary->pph21 ?? 0),
                        ],
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'Daftar karyawan menunggu pembayaran berhasil diambil',
                'data' => $pendingSalaries,
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving pending salary payments: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar karyawan menunggu pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSalaryHistory()
    {
        try {
            $salaries = Salary::with(['masterSalary.user'])
                ->whereHas('masterSalary.user')
                ->orderByDesc('salary_date')
                ->orderByDesc('ID_salary')
                ->get()
                ->map(function ($salary) {
                    $masterSalary = $salary->masterSalary;
                    $grossSalary = (float) ($salary->gross_salary ?? $masterSalary?->salary_amount ?? 0);
                    $fallbackDeduction = max($grossSalary - (float) $salary->salary_amount, 0);
                    $totalDeductions = (float) ($salary->total_deductions ?? $fallbackDeduction);

                    return [
                        'id' => $salary->ID_salary,
                        'id_salary' => $salary->ID_salary,
                        'id_master_salary' => $salary->ID_master_salary,
                        'salary_amount' => (float) $salary->salary_amount,
                        'gross_salary' => $grossSalary,
                        'total_deductions' => $totalDeductions,
                        'salary_date' => $salary->salary_date,
                        'transfer_proof' => $salary->transfer_proof,
                        'created_at' => $salary->created_at,
                        'updated_at' => $salary->updated_at,
                        'employee_name' => $masterSalary?->user?->name ?? 'N/A',
                        'position' => $masterSalary?->position ?? 'N/A',
                        'net_salary' => (float) $salary->salary_amount,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Riwayat slip gaji berhasil diambil',
                'data' => $salaries,
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving salary history: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data riwayat slip gaji',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSalaryDetail($id)
    {
        try {
            $salary = Salary::with(['masterSalary.user'])
                ->where('ID_salary', $id)
                ->first();

            if (!$salary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slip gaji tidak ditemukan',
                ], 404);
            }

            $masterSalary = $salary->masterSalary;
            $user = $masterSalary?->user;

            $grossSalary = (float) ($salary->gross_salary ?? $masterSalary?->salary_amount ?? 0);
            $netSalary = (float) $salary->salary_amount;
            $fallbackPph21 = (float) ($masterSalary?->pph21 ?? max($grossSalary - $netSalary, 0));
            $breakdown = $this->normalizeBreakdown(
                is_array($salary->rule_engine_result) ? $salary->rule_engine_result : [],
                $fallbackPph21
            );

            $totalDeductions = (float) ($salary->total_deductions ?? $breakdown['total_deductions']);
            if ($totalDeductions <= 0 && $grossSalary > 0) {
                $totalDeductions = max($grossSalary - $netSalary, 0);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail slip gaji berhasil diambil',
                'data' => [
                    'id_salary' => $salary->ID_salary,
                    'id_master_salary' => $masterSalary?->id_master_salary,
                    'salary_amount' => $netSalary,
                    'salary_date' => $salary->salary_date,
                    'transfer_proof' => $salary->transfer_proof,
                    'gross_salary' => $grossSalary,
                    'pph21' => $fallbackPph21,
                    'net_salary' => $netSalary,
                    'total_deductions' => round($totalDeductions, 2),
                    'position' => $masterSalary?->position ?? 'N/A',
                    'employee_name' => $user?->name ?? 'N/A',
                    'earnings' => $breakdown['earnings'],
                    'deductions' => $breakdown['deductions'],
                    'calculation_facts' => $salary->calculation_facts,
                    'rule_engine_result' => $salary->rule_engine_result,
                    'created_at' => $salary->created_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving salary detail: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail slip gaji',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_master_salary' => 'required|exists:master_salary,id_master_salary',
            'salary_date' => 'required|date',
            'transfer_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'years_of_service' => 'nullable|integer|min:0|max:60',
            'overtime_minutes' => 'nullable|integer|min:0|max:50000',
            'late_minutes' => 'nullable|integer|min:0|max:50000',
            'unpaid_leave_days' => 'nullable|integer|min:0|max:31',
            'late_deduction_per_minute' => 'nullable|numeric|min:0|max:1000000000',
            'unpaid_leave_per_day' => 'nullable|numeric|min:0|max:1000000000',
            'overtime_per_minute' => 'nullable|numeric|min:0|max:1000000000',
            'tax_flat_amount' => 'nullable|numeric|min:0|max:1000000000',
            'th_r' => 'nullable|numeric|min:0|max:1000000000',
            'thr' => 'nullable|numeric|min:0|max:1000000000',
            'use_manual_attendance' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $masterSalary = MasterSalary::find($request->id_master_salary);

            if (!$masterSalary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master gaji tidak ditemukan',
                ], 404);
            }

            $salaryDate = Carbon::parse($request->salary_date);

            $existingSalary = Salary::where('ID_master_salary', $request->id_master_salary)
                ->whereYear('salary_date', $salaryDate->year)
                ->whereMonth('salary_date', $salaryDate->month)
                ->first();

            if ($existingSalary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slip gaji untuk karyawan ini pada periode tersebut sudah pernah dibuat.',
                ], 422);
            }

            $proofPath = null;
            if ($request->hasFile('transfer_proof')) {
                $file = $request->file('transfer_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $proofPath = $file->storeAs('transfer_proofs', $filename, 'public');
            }

            $facts = $this->buildCalculationFacts($request, $masterSalary);
            $calculation = $this->payrollRuleEngineService->execute($facts);

            $grossSalary = (float) data_get($calculation, 'summary.gross_salary', $masterSalary->salary_amount ?? 0);
            $totalDeductions = (float) data_get($calculation, 'summary.total_deductions', 0);
            $netSalary = (float) data_get($calculation, 'summary.net_salary', $grossSalary - $totalDeductions);

            $grossSalary = round(max($grossSalary, 0), 2);
            $totalDeductions = round(max($totalDeductions, 0), 2);
            $netSalary = round(max($netSalary, 0), 2);

            $ruleResult = [
                'components' => data_get($calculation, 'components', []),
                'earnings' => data_get($calculation, 'earnings', []),
                'deductions' => data_get($calculation, 'deductions', []),
                'summary' => [
                    'basic_salary' => (float) data_get($calculation, 'summary.basic_salary', 0),
                    'gross_salary' => $grossSalary,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                ],
                'engine_response' => data_get($calculation, 'engine_response', []),
            ];

            $salary = Salary::create([
                'ID_master_salary' => $request->id_master_salary,
                'salary_amount' => $netSalary,
                'gross_salary' => $grossSalary,
                'total_deductions' => $totalDeductions,
                'calculation_facts' => data_get($calculation, 'facts', []),
                'rule_engine_result' => $ruleResult,
                'salary_date' => $request->salary_date,
                'transfer_proof' => $proofPath,
            ]);

            $salary->load('masterSalary.user');

            return response()->json([
                'success' => true,
                'message' => 'Slip gaji berhasil ditambahkan dengan perhitungan rule-engine.',
                'data' => [
                    'id_salary' => $salary->ID_salary,
                    'employee_name' => $salary->masterSalary?->user?->name ?? 'N/A',
                    'gross_salary' => $grossSalary,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'salary_date' => $salary->salary_date,
                    'transfer_proof' => $salary->transfer_proof,
                    'earnings' => $ruleResult['earnings'],
                    'deductions' => $ruleResult['deductions'],
                    'calculation_source' => data_get($calculation, 'facts.source', []),
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validasi rules payroll gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $salary = Salary::where('ID_salary', $id)->first();

            if (!$salary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slip gaji tidak ditemukan',
                ], 404);
            }

            if ($salary->transfer_proof && Storage::disk('public')->exists($salary->transfer_proof)) {
                Storage::disk('public')->delete($salary->transfer_proof);
            }

            $salary->delete();

            return response()->json([
                'success' => true,
                'message' => 'Slip gaji berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data slip gaji',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function buildCalculationFacts(Request $request, MasterSalary $masterSalary): array
    {
        $salaryDate = Carbon::parse((string) $request->input('salary_date', now()->toDateString()));
        $basicSalary = (float) ($masterSalary->salary_amount ?? 0);
        $defaultUnpaidPerDay = $basicSalary > 0 ? $basicSalary / 22 : 0;
        $defaultOvertimePerMinute = $basicSalary > 0 ? $basicSalary / (173 * 60) : 0;

        return $this->payrollRuleEngineService->buildFactsFromDatabase($masterSalary, $salaryDate, [
            'employee' => [
                'status' => (string) ($masterSalary->status ?? 'active'),
                'grade' => (string) ($masterSalary->tier_grade ?? ''),
                'years_of_service' => (int) $request->input('years_of_service', 0),
            ],
            'attendance' => [
                'late_minutes' => $request->filled('late_minutes')
                    ? (int) $request->input('late_minutes', 0)
                    : null,
                'unpaid_leave_days' => $request->filled('unpaid_leave_days')
                    ? (int) $request->input('unpaid_leave_days', 0)
                    : null,
                'overtime_minutes' => $request->filled('overtime_minutes')
                    ? (int) $request->input('overtime_minutes', 0)
                    : null,
            ],
            'rates' => [
                'late_deduction_per_minute' => (float) $request->input('late_deduction_per_minute', 1000),
                'unpaid_leave_per_day' => (float) $request->input('unpaid_leave_per_day', $defaultUnpaidPerDay),
                'overtime_per_minute' => (float) $request->input('overtime_per_minute', $defaultOvertimePerMinute),
                'tax_flat_amount' => (float) $request->input('tax_flat_amount', $masterSalary->pph21 ?? 0),
            ],
            'components' => [
                'TH_R' => (float) $request->input('th_r', 0),
                'THR' => (float) $request->input('thr', 0),
            ],
            'prefer_manual_attendance' => filter_var(
                $request->input('use_manual_attendance', false),
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }

    private function normalizeBreakdown(array $ruleEngineResult, float $fallbackPph21): array
    {
        $normalizeItems = function ($items, string $type): Collection {
            return collect($items)
                ->filter(fn ($item) => is_array($item))
                ->map(function ($item) use ($type) {
                    return [
                        'code' => (string) data_get($item, 'code', $type),
                        'name' => (string) data_get($item, 'name', data_get($item, 'code', $type)),
                        'type' => (string) data_get($item, 'type', $type),
                        'amount' => round((float) data_get($item, 'amount', 0), 2),
                    ];
                })
                ->filter(fn ($item) => $item['amount'] != 0)
                ->values();
        };

        $earnings = $normalizeItems(data_get($ruleEngineResult, 'earnings', []), 'EARNING');
        $deductions = $normalizeItems(data_get($ruleEngineResult, 'deductions', []), 'DEDUCTION');

        if ($deductions->isEmpty() && $fallbackPph21 > 0) {
            $deductions->push([
                'code' => 'PPH21',
                'name' => 'PPh 21',
                'type' => 'DEDUCTION',
                'amount' => round($fallbackPph21, 2),
            ]);
        }

        $totalDeductions = (float) data_get($ruleEngineResult, 'summary.total_deductions', $deductions->sum('amount'));

        return [
            'earnings' => $earnings->all(),
            'deductions' => $deductions->all(),
            'total_deductions' => round($totalDeductions, 2),
        ];
    }
}
