<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Salary;
use App\Models\MasterSalary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SalaryController extends Controller
{
    public function getSalaryHistory()
    {
        try {
            $salaries = Salary::with(['masterSalary.user'])
                ->whereHas('masterSalary.user')
                ->get()
                ->map(function ($salary) {
                    return [
                        'id' => $salary->ID_salary, 
                        'id_salary' => $salary->ID_salary,
                        'id_master_salary' => $salary->ID_master_salary,
                        'salary_amount' => $salary->salary_amount, 
                        'salary_date' => $salary->salary_date,
                        'transfer_proof' => $salary->transfer_proof,
                        'created_at' => $salary->created_at,
                        'updated_at' => $salary->updated_at,
                        'employee_name' => $salary->masterSalary->user->name ?? 'N/A',
                        'position' => $salary->masterSalary->position ?? 'N/A',
                        'net_salary' => $salary->masterSalary->net_salary ?? 0,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Riwayat slip gaji berhasil diambil',
                'data' => $salaries
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
            Log::info('Salary ID received:', ['ID_salary' => $id]);

            $salary = Salary::with(['masterSalary.user'])
                ->where('ID_salary', $id)
                ->first();

            if (!$salary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slip gaji tidak ditemukan'
                ], 404);
            }

            $masterSalary = $salary->masterSalary;
            $user = $masterSalary->user ?? null;

            return response()->json([
                'success' => true,
                'message' => 'Detail slip gaji berhasil diambil',
                'data' => [
                    'id_salary'             => $salary->ID_salary,
                    'id_master_salary'      => $masterSalary->id_master_salary ?? null,
                    'salary_amount'         => $salary->salary_amount, // Net salary
                    'salary_date'           => $salary->salary_date,
                    'transfer_proof'        => $salary->transfer_proof,

                    // Data master salary
                    'gross_salary'          => $masterSalary->salary_amount ?? 0, // Gaji kotor
                    'pph21'                 => $masterSalary->pph21 ?? 0, // PPh 21
                    'net_salary'            => $masterSalary->net_salary ?? 0, // Gaji bersih
                    'position'              => $masterSalary->position ?? 'N/A',

                    // Data user terkait
                    'employee_name'         => $user->name ?? 'N/A',

                    'created_at'            => $salary->created_at,
                ]
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
            'transfer_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Dapatkan data master salary untuk mengambil net_salary
            $masterSalary = MasterSalary::find($request->id_master_salary);
            
            if (!$masterSalary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master gaji tidak ditemukan',
                ], 404);
            }

            // Gunakan net_salary dari master_salary
            $netSalary = $masterSalary->net_salary ?? $masterSalary->salary_amount;

            $proofPath = null;
            if ($request->hasFile('transfer_proof')) {
                $file = $request->file('transfer_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('transfer_proofs', $filename, 'public');
                $proofPath = $path;
            }

            $salary = Salary::create([
                'ID_master_salary' => $request->id_master_salary,
                'salary_amount' => $netSalary, // Simpan net_salary
                'salary_date' => $request->salary_date,
                'transfer_proof' => $proofPath
            ]);

            // Load relationship for response
            $salary->load('masterSalary.user');

            return response()->json([
                'success' => true,
                'message' => 'Slip gaji berhasil ditambahkan',
                'data' => [
                    'id_salary' => $salary->ID_salary,
                    'employee_name' => $salary->masterSalary->user->name ?? 'N/A',
                    'gross_salary' => $masterSalary->salary_amount,
                    'pph21' => $masterSalary->pph21 ?? 0,
                    'net_salary' => $netSalary,
                    'salary_date' => $salary->salary_date,
                    'transfer_proof' => $salary->transfer_proof
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function updateTransferProof(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'transfer_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validasi gagal',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     try {
    //         $salary = Salary::find($id);

    //         if (!$salary) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Data slip gaji tidak ditemukan',
    //             ], 404);
    //         }

    //         // Hapus file lama jika ada
    //         if ($salary->transfer_proof && Storage::disk('public')->exists($salary->transfer_proof)) {
    //             Storage::disk('public')->delete($salary->transfer_proof);
    //         }

    //         // Simpan file baru
    //         $file = $request->file('transfer_proof');
    //         $proofPath = $file->store('transfer_proofs', 'public');

    //         $salary->transfer_proof = $proofPath;
    //         $salary->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Bukti transfer berhasil diupdate',
    //             'data' => $salary
    //         ], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Error updating transfer proof: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan saat mengupdate bukti transfer',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function destroy($id)
    {
        try {
            $salary = Salary::where('ID_salary', $id)->first();

            if (!$salary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slip gaji tidak ditemukan'
                ], 404);
            }

            // Hapus file bukti transfer jika ada
            if ($salary->transfer_proof && Storage::disk('public')->exists($salary->transfer_proof)) {
                Storage::disk('public')->delete($salary->transfer_proof);
            }

            $salary->delete();

            return response()->json([
                'success' => true,
                'message' => 'Slip gaji berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data slip gaji',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}