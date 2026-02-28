<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $contracts = Contract::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($contract) {
                    return [
                        'contract_id' => $contract->id_contract,
                        'contract_number' => $contract->contract_number,
                        'contract_date' => $contract->contract_date->format('Y-m-d'),
                        'employee' => [
                            'id' => $contract->user->id,
                            'name' => $contract->user->name,
                            'email' => $contract->user->email,

                        ],
                        'start_contract' => $contract->start_contract->format('Y-m-d'),
                        'end_contract' => $contract->end_contract->format('Y-m-d'),
                        'contract_duration' => $this->calculateContractDuration($contract->start_contract, $contract->end_contract),
                        'remaining_days' => $this->calculateRemainingDays($contract->end_contract),
                        'leave_stock' => $contract->leave_stock,
                        'status' => $contract->status,
                        'status_badge' => $this->getStatusBadge($contract->status),
                        'created_at' => $contract->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $contract->updated_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Employee contracts retrieved successfully',
                'data' => $contracts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve contracts',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($contractId): JsonResponse
    {
        try {
            $contract = Contract::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'position', 'department');
                }
            ])
                ->where('id_contract', $contractId)
                ->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found'
                ], 404);
            }

            $contractData = [
                'contract_id' => $contract->id_contract,
                'contract_number' => $contract->contract_number,
                'contract_date' => $contract->contract_date->format('Y-m-d'),
                'employee' => [
                    'id' => $contract->user->id,
                    'name' => $contract->user->name,
                    'email' => $contract->user->email,
                    'position' => $contract->user->position,
                    'department' => $contract->user->department
                ],
                'contract_period' => [
                    'start_date' => $contract->start_contract->format('Y-m-d'),
                    'end_date' => $contract->end_contract->format('Y-m-d'),
                    'duration' => $this->calculateContractDuration($contract->start_contract, $contract->end_contract),
                    'remaining_days' => $this->calculateRemainingDays($contract->end_contract),
                    'is_active' => $contract->end_contract->isFuture()
                ],
                'leave_stock' => $contract->leave_stock,
                'status' => $contract->status,
                'status_info' => $this->getStatusInfo($contract->status),
                'timestamps' => [
                    'created_at' => $contract->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $contract->updated_at->format('Y-m-d H:i:s')
                ]
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Contract details retrieved successfully',
                'data' => $contractData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve contract details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|uuid|exists:users,id',
            'contract_number' => 'required|string|max:100|unique:contract,contract_number',
            'contract_date' => 'required|date',
            'start_contract' => 'required|date',
            'end_contract' => 'required|date|after:start_contract',
            // 'leave_stock' => 'required|integer|min:0',
            // 'status' => 'required|in:process,approved,revision,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contract = Contract::create([
                'id_user' => $request->id_user,
                'contract_number' => $request->contract_number,
                'contract_date' => $request->contract_date,
                'start_contract' => $request->start_contract,
                'end_contract' => $request->end_contract
                // 'leave_stock' => 3,
                // 'status' => "process"
            ]);

            $contract->load('user:id,name,email');

            return response()->json([
                'status' => 'success',
                'message' => 'Contract created successfully',
                'data' => $contract
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $contractId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contract_number' => 'sometimes|string|max:100|unique:contract,contract_number,' . $contractId . ',id_contract',
            'contract_date' => 'sometimes|date',
            'start_contract' => 'sometimes|date',
            'end_contract' => 'sometimes|date|after:start_contract',
            'leave_stock' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:process,approved,revision,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contract = Contract::where('id_contract', $contractId)->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found'
                ], 404);
            }

            $contract->update($request->all());
            $contract->load('user:id,name,email,position');

            return response()->json([
                'status' => 'success',
                'message' => 'Contract updated successfully',
                'data' => $contract
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function filterByStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:process,approved,revision,suspended'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contracts = Contract::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'position');
                }
            ])
                ->where('status', $request->status)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($contract) {
                    return [
                        'contract_id' => $contract->id_contract,
                        'contract_number' => $contract->contract_number,
                        'employee_name' => $contract->user->name,
                        'employee_position' => $contract->user->position,
                        'start_contract' => $contract->start_contract->format('Y-m-d'),
                        'end_contract' => $contract->end_contract->format('Y-m-d'),
                        'contract_duration' => $this->calculateContractDuration($contract->start_contract, $contract->end_contract),
                        'leave_stock' => $contract->leave_stock,
                        'status' => $contract->status,
                        'created_at' => $contract->created_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Contracts filtered by status retrieved successfully',
                'data' => [
                    'status' => $request->status,
                    'count' => $contracts->count(),
                    'contracts' => $contracts
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to filter contracts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateContractDuration($startDate, $endDate): string
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        $months = $start->diffInMonths($end);
        $days = $start->copy()->addMonths($months)->diffInDays($end);

        if ($months > 0 && $days > 0) {
            return "{$months} months {$days} days";
        } elseif ($months > 0) {
            return "{$months} months";
        } else {
            return "{$days} days";
        }
    }

    private function calculateRemainingDays($endDate): int
    {
        $end = \Carbon\Carbon::parse($endDate);
        $now = \Carbon\Carbon::now();

        if ($now->gt($end)) {
            return 0;
        }

        return $now->diffInDays($end);
    }

    private function getStatusBadge($status): string
    {
        $badges = [
            'process' => 'warning',
            'approved' => 'success',
            'revision' => 'info',
            'suspended' => 'danger'
        ];

        return $badges[$status] ?? 'secondary';
    }


    private function getStatusInfo($status): array
    {
        $statusInfo = [
            'process' => ['label' => 'In Process', 'color' => 'orange'],
            'approved' => ['label' => 'Approved', 'color' => 'green'],
            'revision' => ['label' => 'Need Revision', 'color' => 'blue'],
            'suspended' => ['label' => 'Suspended', 'color' => 'red']
        ];

        return $statusInfo[$status] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    public function approvalList(Request $request): JsonResponse
    {
        try {
            $contracts = Contract::where('status', 'process')->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'position');
                }
            ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($contract, $index) {
                    return [
                        'no' => $index + 1,
                        'contract_id' => $contract->id_contract,
                        'contract_number' => $contract->contract_number,
                        'contract_date' => $contract->contract_date->format('d F Y'), // Format: 1 September 2025
                        'status' => $this->getApprovalStatus($contract->status),
                        'status_original' => $contract->status,
                        'actions' => $this->getApprovalActions($contract->status),
                        'employee' => [
                            'name' => $contract->user->name,
                            'position' => $contract->user->position
                        ]
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Contract approval list retrieved successfully',
                'data' => $contracts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve contract approval list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approvalContract(Request $request, $contractId): JsonResponse
    {
        // $validator = Validator::make($request->all(), [
        //     'approval_status' => 'required|in:approved,revision,suspended'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors()
        //     ], 422);
        // }

        try {
            $contract = Contract::where('id_contract', $contractId)
            ->where('status','approved')->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found'
                ], 404);
            }

            $contract->update(['status' => 'done']);

            return response()->json([
                'status' => 'success',
                'message' => 'Contract approval status updated successfully',
                'data' => $contract
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update contract approval status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function approvalContractDirector(Request $request, $contractId): JsonResponse
    {

        try {
            $contract = Contract::where('id_contract', $contractId)
            ->where('status','process')->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found'
                ], 404);
            }

            $contract->update(['status' => 'approved']);

            return response()->json([
                'status' => 'success',
                'message' => 'Contract approval status updated successfully',
                'data' => $contract
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update contract approval status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Untuk karyawan melihat daftar kontrak mereka sendiri
    public function employeeContractList(Request $request): JsonResponse
    {
        try {
            // Asumsi: user yang login adalah karyawan, dapat id user dari auth
            $userId = auth()->id();

            $contracts = Contract::where('id_user', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($contract, $index) {
                    return [
                        'no' => $index + 1,
                        'contract_number' => $contract->contract_number,
                        'contract_date' => $contract->contract_date->format('d F Y'),
                        'status' => $this->getEmployeeContractStatus($contract->status),
                        'actions' => $this->getEmployeeContractActions($contract->status),
                        'status_original' => $contract->status
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Employee contracts retrieved successfully',
                'data' => $contracts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve employee contracts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function employeeContractDetail(Request $request, $contractId): JsonResponse
    {
        try {
            // $user = auth()->id();
            $user = $request->input('id_user');

            $contract = Contract::with([
                'user' => function ($query) {
                    // $query->select('id', 'name', 'email', 'position', 'department');
                    $query->select('id', 'name', 'email');
                }
            ])
                ->where('id_contract', $contractId)
                // ->where('id_user', $user) // <- INI BEDANYA! Hanya kontrak milik sendiri
                // ->where('id_user', $user) // Hanya kontrak milik user yang login
                ->whereHas('user', function ($query) {
                    $query->where('role', 'karyawan');
                })
                ->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found or access denied'
                ], 404);
            }

            $contractData = [
                'contract_id' => $contract->id_contract,
                'contract_number' => $contract->contract_number,
                'contract_date' => $contract->contract_date->format('d F Y'), // Format lebih user-friendly
                'employee' => [
                    'id' => $contract->user->id,
                    'name' => $contract->user->name,
                    'email' => $contract->user->email,
                    'position' => $contract->user->position,
                    'department' => $contract->user->department
                ],
                'contract_period' => [
                    'start_date' => $contract->start_contract->format('d F Y'),
                    'end_date' => $contract->end_contract->format('d F Y'),
                    'duration' => $this->calculateContractDuration($contract->start_contract, $contract->end_contract),
                    'remaining_days' => $this->calculateRemainingDays($contract->end_contract),
                    'is_active' => $contract->end_contract->isFuture()
                ],
                // 'leave_entitlement' => [ // <- Label lebih user-friendly untuk karyawan
                //     'total_leave' => $contract->leave_stock,
                //     'remaining_leave' => $contract->leave_stock, // Bisa disesuaikan dengan logika cuti terpakai
                //     'used_leave' => 0
                // ],
                // 'status' => $this->getStatusDisplay($contract->status), // <- Status dalam Bahasa Indonesia
                // 'status_original' => $contract->status,
                // 'status_info' => $this->getStatusInfo($contract->status),
                // 'contract_history' => [ // <- Label lebih user-friendly
                //     'created_at' => $contract->created_at->format('d F Y H:i:s'),
                //     'updated_at' => $contract->updated_at->format('d F Y H:i:s')
                // ]
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Contract details retrieved successfully',
                'data' => $contractData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve contract details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //pm
    public function ContractListPM(Request $request): JsonResponse
    {
        try {
            $userId = auth()->id();

            $contracts = Contract::where('id_user', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($contract, $index) {
                    return [
                        'no' => $index + 1,
                        'contract_number' => $contract->contract_number,
                        'contract_date' => $contract->contract_date->format('d F Y'),
                        'status' => $this->getEmployeeContractStatus($contract->status),
                        'actions' => $this->getEmployeeContractActions($contract->status),
                        'status_original' => $contract->status
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'PM contracts retrieved successfully',
                'data' => $contracts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pm contracts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ContractDetailPM(Request $request, $contractId): JsonResponse
    {
        try {
            // $user = auth()->id();
            $user = $request->input('id_user');

            $contract = Contract::with([
                'user' => function ($query) {
                    // $query->select('id', 'name', 'email', 'position', 'department');
                    $query->select('id', 'name', 'email');
                }
            ])
                ->where('id_contract', $contractId)
                // ->where('id_user', $user) // <- INI BEDANYA! Hanya kontrak milik sendiri
                // ->where('id_user', $user) // Hanya kontrak milik user yang login
                ->whereHas('user', function ($query) {
                    $query->where('role', 'project manager');
                })
                ->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found or access denied'
                ], 404);
            }

            $contractData = [
                'contract_id' => $contract->id_contract,
                'contract_number' => $contract->contract_number,
                'contract_date' => $contract->contract_date->format('d F Y'), // Format lebih user-friendly
                'pm' => [
                    'id' => $contract->user->id,
                    'name' => $contract->user->name,
                    'email' => $contract->user->email,
                    'position' => $contract->user->position,
                    'department' => $contract->user->department
                ],
                'contract_period' => [
                    'start_date' => $contract->start_contract->format('d F Y'),
                    'end_date' => $contract->end_contract->format('d F Y'),
                    'duration' => $this->calculateContractDuration($contract->start_contract, $contract->end_contract),
                    'remaining_days' => $this->calculateRemainingDays($contract->end_contract),
                    'is_active' => $contract->end_contract->isFuture()
                ],
                // 'leave_entitlement' => [ // <- Label lebih user-friendly untuk karyawan
                //     'total_leave' => $contract->leave_stock,
                //     'remaining_leave' => $contract->leave_stock, // Bisa disesuaikan dengan logika cuti terpakai
                //     'used_leave' => 0
                // ],
                // 'status' => $this->getStatusDisplay($contract->status), // <- Status dalam Bahasa Indonesia
                // 'status_original' => $contract->status,
                // 'status_info' => $this->getStatusInfo($contract->status),
                // 'contract_history' => [ // <- Label lebih user-friendly
                //     'created_at' => $contract->created_at->format('d F Y H:i:s'),
                //     'updated_at' => $contract->updated_at->format('d F Y H:i:s')
                // ]
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Contract details retrieved successfully',
                'data' => $contractData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve contract details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approvalContractPM(Request $request, $contractId): JsonResponse
    {
        // $validator = Validator::make($request->all(), [
        //     'approval_status' => 'required|in:approved,revision,suspended'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors()
        //     ], 422);
        // }

        try {
            $contract = Contract::where('id_contract', $contractId)
            ->where('status','approved')->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found'
                ], 404);
            }

            $contract->update(['status' => 'done']);

            return response()->json([
                'status' => 'success',
                'message' => 'Contract approval status updated successfully',
                'data' => $contract
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update contract approval status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
