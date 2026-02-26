<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Models\MasterSalary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MasterSalaryController extends Controller
{

    /**
     * Hitung PPh 21 berdasarkan gaji
     */
    private function calculatePPh21($salaryAmount)
    {
        // Gaji setahun
        $yearlySalary = $salaryAmount * 12;
        $ptkp = 54000000; // PTKP untuk tidak kawin/tanpa tanggungan

        // Penghasilan kena pajak
        $pkp = max(0, $yearlySalary - $ptkp);

        // Tarif progresif PPh 21 (sesuai UU PPh)
        $pph21 = 0;

        if ($pkp > 0) {
            if ($pkp <= 60000000) {
                $pph21 = $pkp * 0.05;
            } elseif ($pkp <= 250000000) {
                $pph21 = (60000000 * 0.05) + (($pkp - 60000000) * 0.15);
            } elseif ($pkp <= 500000000) {
                $pph21 = (60000000 * 0.05) + (190000000 * 0.15) + (($pkp - 250000000) * 0.25);
            } else {
                $pph21 = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + (($pkp - 500000000) * 0.30);
            }
        }

        return $pph21 / 12;
    }

    public function index(): JsonResponse
    {
        try {
            $masterSalary = MasterSalary::with('user:id,name')->get();

            Log::info('Master salaries data:', ['count' => $masterSalary->count()]);

            return response()->json([
                'success' => true,
                'data' => $masterSalary,
                'message' => 'Data master salary berhasil diambil'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching master salaries: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data master salary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|uuid',
            'salary_amount' => 'required|numeric|between:0,999999999999.99',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $userExists = DB::table('users')->where('id', $request->id_user)->exists();

            if (!$userExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            if ($request->status === 'active') {
                MasterSalary::where('id_user', $request->id_user)
                    ->where('status', 'active')
                    ->update(['status' => 'inactive']);
            }

            // Hitung PPh 21
            $pph21 = $this->calculatePPh21($request->salary_amount);
            $netSalary = $request->salary_amount - $pph21;

            $masterSalary = MasterSalary::create([
                'id_user' => $request->id_user,
                'salary_amount' => $request->salary_amount,
                'pph21' => $pph21, // Tambahkan kolom pph21
                'net_salary' => $netSalary, // Tambahkan kolom net_salary
                'status' => $request->status
            ]);

            DB::commit();

            Log::info('Master salary created:', [
                'id_master_salary' => $masterSalary->id_master_salary,
                'gross_salary' => $request->salary_amount,
                'pph21' => $pph21,
                'net_salary' => $netSalary
            ]);

            return response()->json([
                'success' => true,
                'data' => $masterSalary,
                'message' => 'Master salary berhasil dibuat dengan perhitungan PPh 21'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating master salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat master salary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'salary_amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'pph21' => 'nullable|numeric|min:0', // Tambahkan validasi untuk PPh 21
            'net_salary' => 'nullable|numeric|min:0', // Tambahkan validasi untuk net salary
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $masterSalary = MasterSalary::find($id);

            if (!$masterSalary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master gaji tidak ditemukan',
                ], 404);
            }

            // Update data
            $masterSalary->salary_amount = $request->salary_amount;
            $masterSalary->status = $request->status;

            // Update PPh 21 dan net_salary jika dikirim dari frontend
            if ($request->has('pph21')) {
                $masterSalary->pph21 = $request->pph21;
            }

            if ($request->has('net_salary')) {
                $masterSalary->net_salary = $request->net_salary;
            } else {
                // Jika net_salary tidak dikirim, hitung ulang
                $masterSalary->net_salary = $request->salary_amount - ($masterSalary->pph21 ?? 0);
            }

            $masterSalary->save();

            return response()->json([
                'success' => true,
                'message' => 'Data master gaji berhasil diupdate',
                'data' => [
                    'id_master_salary' => $masterSalary->id_master_salary,
                    'employee_name' => $masterSalary->user->name ?? 'N/A',
                    'salary_amount' => $masterSalary->salary_amount,
                    'pph21' => $masterSalary->pph21,
                    'net_salary' => $masterSalary->net_salary,
                    'status' => $masterSalary->status,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating master salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $masterSalary = MasterSalary::with('user:id,name')->find($id);

            if (!$masterSalary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master salary tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $masterSalary,
                'message' => 'Data master salary berhasil diambil'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching master salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data master salary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $masterSalary = MasterSalary::find($id);

            if (!$masterSalary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master salary tidak ditemukan'
                ], 404);
            }

            $masterSalary->delete();

            DB::commit();
            Log::info('Master salary deleted successfully:', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Master salary berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error deleting master salary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus master salary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
